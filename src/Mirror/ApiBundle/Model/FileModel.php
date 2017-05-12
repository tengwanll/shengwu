<?php

namespace Mirror\ApiBundle\Model;

use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation\Service;
use Mirror\ApiBundle\Entity\File;
use Mirror\ApiBundle\Util\Helper;

/**
 * 文件管理
 * @Service("file_model", parent="base_model")
 * Class FileModel
 * @package Mirror\ApiBundle\Model
 */
class FileModel extends BaseModel {

    private $repositoryName = 'MirrorApiBundle:File';

    public function getRepositoryName() {
        return $this->repositoryName;
    }

    /**
     * @param $id
     * @return \Mirror\ApiBundle\Entity\File
     */
    public function getById($id) {
        return parent::getById($id);
    }

    /**
     * 根据id获取文件地址
     * @param $id
     * @return string
     */
    public function getUrlById($id) {
        $file = $this->getById($id);

        return $file ? $file->getUrl() : '';
    }

    /**
     * 检索文件
     * @param $arguments
     * @return int|null|object
     */
    public function retrieve($arguments) {
        $id = Helper::getc($arguments, 'id', 0);
        $md5 = Helper::getc($arguments, 'md5', '');
        $criteria = array();
        if ($id != 0) {
            $criteria = array_merge(
                $criteria,
                array(
                    'id' => $id,
                )
            );
        }
        if ($md5 != '') {
            $criteria = array_merge(
                $criteria,
                array(
                    'md5' => $md5,
                )
            );
        }
        if (count($criteria) == 0) {
            return 0;
        }

        return $this->getEntityRepository()->findOneBy($criteria);
    }

    /**
     * 保存文件信息
     * @param $fileName
     * @param $md5
     * @param $url
     * @param $size
     * @return \Mirror\ApiBundle\Entity\File
     */
    public function create($fileName, $md5, $url, $size) {
        $file = new File();
        $file->setMd5($md5);
        $file->setSize($size);
        $file->setFileName($fileName);
        $file->setUrl($url);
        $file->setCreateTime(new \DateTime());
        $file->setUpdateTime(new \DateTime());

        return $this->save($file);
    }
}