<?php

namespace Mirror\ApiBundle\Service;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use Mirror\ApiBundle\Common\Code;
use Mirror\ApiBundle\Common\Constant;
use Mirror\ApiBundle\Entity\File;
use Mirror\ApiBundle\Model\FileModel;
use Mirror\ApiBundle\Model\SystemSettingModel;
use Mirror\ApiBundle\ViewModel\ReturnResult;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Service("file_service")
 * Class FileService
 * @package Mirror\ApiBundle\Service
 */
class FileService {

    private $fileModel;
    private $systemSettingModel;
    private $rootDir;

    /**
     * @InjectParams({
     *     "fileModel" = @Inject("file_model"),
     *     "systemSettingModel" = @Inject("system_setting_model"),
     *     "rootDir" = @Inject("%kernel.root_dir%"),
     * })
     * FileService constructor.
     * @param FileModel $fileModel
     * @param SystemSettingModel $systemSettingModel
     * @param string $rootDir
     */
    public function __construct(FileModel $fileModel, SystemSettingModel $systemSettingModel,$rootDir) {
        $this->fileModel = $fileModel;
        $this->systemSettingModel = $systemSettingModel;
        $this->rootDir = $rootDir;
    }

    public function getStorage() {
        return '/upload';
    }

    /**
     * 保存文件
     * @param Request $request
     * @param resource $fileObject
     * @return bool|File
     */
    public function saveFile(Request $request,$fileObject) {
        if (!$fileObject->isValid()){
            return false;
        }
        $fileOriginalName = $fileObject->getClientOriginalName();

        // $filename = preg_replace('/[\/:*?"<>|&+ \t]+/', '_', $fileObject->getClientOriginalName());
        $fileSize = $fileObject->getClientSize();
        $md5 = md5_file($fileObject->getPathname());
        $extension = $this->getExtension($fileOriginalName);
        $filename = $md5.'.'.$extension;
        $file = $this->fileModel->retrieve(
            array(
                'md5' => $md5,
            )
        );

        if ($file) {
            return $file;
        }

        $baseDir = preg_replace('/app$/si', 'web'.$request->get('folder'), $this->rootDir);
        $hashDir = sprintf('%s/%s/%s/%s', $md5 [0], $md5 [1], $md5 [2], $md5 [3]);
        $destDir = sprintf('%s/%s/%s', $baseDir, $this->getStorage(), $hashDir);
        $filePath = sprintf('%s/%s/%s', $this->getStorage(), $hashDir, $filename);
        if (!is_dir($destDir)) {
            if (!mkdir($destDir, 0755, true)) {
                return false;
            }
        }

        try {
            $fileObject->move($destDir, $filename);
        } catch (FileException $e) {
            return false;
        }

        return $this->fileModel->create($fileOriginalName, $md5,$filePath ,$fileSize );
    }

    /**
     * 获取文件地址
     * @param $id
     * @return null|string
     */
    public function getFullUrlById($id) {
        if (!$id) {
            return '';
        }

        $file = $this->fileModel->getById($id);
        if (!$file) {
            return '';
        }

        return $this->getFullUrl($file);
    }

    /**
     * 获取文件地址
     * @deprecated
     * @param File $file
     * @return string
     */
    public function getFullUrl(File $file) {
        if (!$file) {
            return '';
        }
        $downLoadBaseUrl = $this->systemSettingModel->getDownLoadBaseUrl();

        return $downLoadBaseUrl.$file->getUrl();
    }

    function getExtension($file) {
        return substr(strrchr($file, '.'), 1);
    }

    public function ossCallBack($post){
        $rr=new ReturnResult();
        $file=$this->fileModel->create('','',$post['filename'],$post['size']);
        $this->logCurlModel->create('',json_encode($post),200,'','post','oss回调');
        $rr->result=array(
            'id'=>$file->getId()
        );
        return $rr;
    }

    /**
     * oss获取签名
     * @return string
     */
    public function getSecret(){
        $id= 'ShyzHMxaZissAqY1';
        $key= 'krtuEdBZBECDdhiGACS7vydEZ5v6zh';
        $host = 'http://mirror2016zhinengitu.oss-cn-qingdao.aliyuncs.com';
        $callbackUrl = "http://115.29.43.27:8006/api/file/oss/callback";

        $callback_param = array('callbackUrl'=>$callbackUrl,
            'callbackBody'=>'filename=${object}&size=${size}',
            'callbackBodyType'=>"application/x-www-form-urlencoded");
        $callback_string = json_encode($callback_param);

        $base64_callback_body = base64_encode($callback_string);
        $now = time();
        $expire = 30; //设置该policy超时时间是10s. 即这个policy过了这个有效时间，将不能访问
        $end = $now + $expire;
        $expiration = $this->gmt_iso8601($end);

        $dir = 'direction/video';

        //最大文件大小.用户可以自己设置
        $condition = array(0=>'content-length-range', 1=>0, 2=>1048576000);
        $conditions[] = $condition;

        //表示用户上传的数据,必须是以$dir开始, 不然上传会失败,这一步不是必须项,只是为了安全起见,防止用户通过policy上传到别人的目录
        $start = array(0=>'starts-with', 1=>'$key', 2=>$dir);
        $conditions[] = $start;


        $arr = array('expiration'=>$expiration,'conditions'=>$conditions);
        $policy = json_encode($arr);
        $base64_policy = base64_encode($policy);
        $string_to_sign = $base64_policy;
        $signature = base64_encode(hash_hmac('sha1', $string_to_sign, $key, true));

        $response = array();
        $response['accessid'] = $id;
        $response['host'] = $host;
        $response['policy'] = $base64_policy;
        $response['signature'] = $signature;
        $response['expire'] = $end;
        $response['callback'] = $base64_callback_body;
        //这个参数是设置用户上传指定的前缀
        $response['dir'] = $dir;
        return json_encode($response);
    }

    public function gmt_iso8601($time) {
        $dtStr = date("c", $time);
        $mydatetime = new \DateTime($dtStr);
        $expiration = $mydatetime->format(\DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);
        return $expiration."Z";
    }
}
