<?php
/**
 * @author : Gaellan
 * @link : https://github.com/Gaellan
 */


class CategoryManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function findAll()
    {
        $query = $this->db->prepare('SELECT * FROM categories');
        $query->execute();
        
        $categories = $query->fetchAll(PDO::FETCH_ASSOC);
        $categoriesAll = [];
        foreach($categories as $categorie){
            $category = new Category(
                $categorie['title'],
                $categorie['description']
                );
                
                $category->setId((int)$categorie['id']);
                $categoriesAll[] = $category;
        }
        return $categoriesAll;
    }
    
    public function findOne(int $id)
    {
        $query = $this->db->prepare('SELECT * FROM categories where id = :id');
        
        $parameters = ['id' => $id];
        
        $query->execute($parameters);
        
        $categorie = $query->fetch(PDO::FETCH_ASSOC);
        
        if (!$categorie) {
            return null;
        }
        
        $categoriesOne = new Category(
            $categorie['title'],
            $categorie['description']
        );
        
        $categoriesOne->setId((int)$categorie['id']);
        
        return $categoriesOne;
    }
    
    public function findByPost(int $postId): array
    {
        $query = $this->db->prepare('
            SELECT * FROM categories
            JOIN posts_categories ON categories.id = posts_categories.category_id
            WHERE posts_categories.post_id = :postId
            ');
        
        $query->execute(['postId' => $postId]);
        
        $categoriesData = $query->fetchAll(PDO::FETCH_ASSOC);
        $categories = [];
        foreach($categoriesData as $categorie){
            $category = new Category(
                $categorie['title'],
                $categorie['description']
            );
            $category->setId((int)$categorie['id']);
            $categories[] = $category;
        }
        return $categories;
    }
}