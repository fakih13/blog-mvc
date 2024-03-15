<?php

namespace App\Model;

use App\Lib\Database;


/**
 * class to manage post
 */

class PostModel
{

    /**
     * @var establish a PDO connection
     */
    protected $database;

    public function __construct()
    {
        $this->database = new Database();
    }


    public function addAPostInSql()
    {
        $savePostInSql = "INSERT INTO `post`(`title`, `content`, `created_at`, `updated_at`, `authorId`, `metaTitle`, `slug`, `summary`, `published`, `publishedAt`) VALUES (:title, :content, :created_at, :updated_at, :authorId, :metaTitle, :slug, :summary, :published, :publishedAt)";
        $savePostCategoryInSql = "INSERT INTO `post_category`(`postId`, `categoryId`) VALUES (':postId',':categoryId')";

        /* 
        // ajout de nouveau(x) tag(s)
        $savingTagsInSql = "INSERT INTO `tag`(`id`, `title`, `metaTitle`, `slug`, `content`) VALUES (:id, :title, :metaTitle, :slug, :content)";


        // tag du post
        $savingPostTagsInSql = "INSERT INTO `post_tag`(`postId`, `tagId`) VALUES (:postId, :tagId)"; 
        */

        /* $savePostMetaInSql = ""; */
        /*
        $stmt->bindParam(':title', $value2);
        $stmt->bindParam(':content', $value3);
        $stmt->bindParam(':created_at', $value4);
        $stmt->bindParam(':updated_at', $value5);
        $stmt->bindParam(':authorId', $value6);
        $stmt->bindParam(':metaTitle', $value7);
        $stmt->bindParam(':slug', $value8);
        $stmt->bindParam(':summary', $value9);
        $stmt->bindParam(':published', $value10);
        $stmt->bindParam(':publishedAt', $value11);

        // tag
        $stmt->bindParam(':postId', $value1);
        $stmt->bindParam(':tagId', $value2);
        */
    }

    
}
