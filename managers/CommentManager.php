<?php
/**
 * @author : Gaellan
 * @link : https://github.com/Gaellan
 */


class CommentManager extends AbstractManager
{
    public function __construct()
        {
            parent::__construct();
        }
        
    public function findByPost(int $postId): array
    {
        $query = $this->db->prepare('SELECT * FROM comments WHERE post_id = :postId');
        $query->execute(['postId' => $postId]);

        $commentsData = $query->fetchAll(PDO::FETCH_ASSOC);
        $comments = [];
        foreach ($commentsData as $commentData) {
            $comment = new Comment(
                $commentData['content'],
                $commentData['author'],
                (int)$commentData['post_id'],
                new DateTime($commentData['created_at'])
                );
            $comment->setId((int)$commentData['id']);
            $comments[] = $comment;
        }
        return $comments;
    }
    public function create(Comment $comment): bool
    {
        $query = $this->db->prepare('
            INSERT INTO comments (content, author, post_id, created_at)
            VALUES (:content, :author, :post_id, :created_at)
        ');

        $parameters = [
            'content' => $comment->getContent(),
            'author' => $comment->getAuthor(),
            'post_id' => $comment->getPostId(),
            'created_at' => $comment->getCreatedAt()

        return $query->execute($parameters);
    }
}