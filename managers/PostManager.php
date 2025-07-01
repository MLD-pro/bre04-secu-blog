<?php
/**
 * @author : Gaellan
 * @link : https://github.com/Gaellan
 */


class PostManager extends AbstractManager
{
    public function __construct()
        {
        parent::__construct();
        }
        
         public function findLatest(): array
    {
        $query = $this->db->prepare('SELECT * FROM posts ORDER BY created_at DESC LIMIT 4');
        $query->execute();

        $postsData = $query->fetchAll(PDO::FETCH_ASSOC);
        $posts = [];
        foreach ($postsData as $postData) {
            $post = new Post(
                $postData['title'],
                $postData['excerpt'],
                $postData['content'],
                $postData['author'],
                new DateTime($postData['created_at'])
            );
            $post->setId((int)$postData['id']);
            
            $posts[] = $post;
        }
        return $posts;
    }
    
        public function findOne(int $id): ?Post
        {
            $query = $this->db->prepare('SELECT * FROM posts WHERE id = :id');
            $query->execute(['id' => $id]);
    
            $postData = $query->fetch(PDO::FETCH_ASSOC);
    
            if (!$postData) {
                return null;
            }
    
            $post = new Post(
                $postData['title'],
                $postData['excerpt'],
                $postData['content'],
                $postData['author'],
                new DateTime($postData['created_at'])
        );
        $post->setId((int)$postData['id']);
        
        return $post;
    }
        public function findByCategory(int $categoryId): array
        {
        
            $query = $this->db->prepare('
                SELECT * FROM posts
                JOIN posts_categories ON posts.id = posts_categories.post_id
                WHERE posts_categories.category_id = :categoryId
            ');
            $query->execute(['categoryId' => $categoryId]);
    
            $postsData = $query->fetchAll(PDO::FETCH_ASSOC);
            $posts = [];
            foreach ($postsData as $postData) {
                $post = new Post(
                    $postData['title'],
                    $postData['excerpt'],
                    $postData['content'],
                    $postData['author'], 
                    new DateTime($postData['created_at'])
            );
            $post->setId((int)$postData['id']);
            
            $posts[] = $post;
        }
        return $posts;
    }
}