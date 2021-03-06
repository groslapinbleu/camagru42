<?php
require __DIR__ . '/../config/database.php';

class Image
{
    public $id;
    public $user_id;
    public $image_name;
    public $creation_date;

    public function __construct($data = null)
    {
        if (is_array($data)) {
            if (isset($data['user_id']))
                $this->user_id = $data['user_id'];
            if (isset($data['image_name']))
                $this->image_name = $data['image_name'];
        }
    }


    public function listBestPhotos($db)
    {
        // recupere les meilleurs photos de tous les utilisateurs avec leur nom, leur propietaire et leur nombre de likes
        // et les renvoie dans un tableau
        $statement = $db->prepare("SELECT i.id, i.image_name, i.user_id, count(l.id) likes FROM Image i INNER JOIN like_table l ON i.id = l.image_id GROUP BY i.id, i.image_name, i.user_id ORDER BY likes DESC");
        $statement->execute();
        $result = $statement->fetchAll();
        return $result;
    }

    public function listAllPhotos($db)
    {
        // recupere toutes les photos par ordre de creation descendant et les renvoie dans un tableau
        $statement = $db->prepare("SELECT id, image_name, user_id FROM Image ORDER BY creation_date DESC");
        $statement->execute();
        $result = $statement->fetchAll();
        return $result;
    }

    public function listBlockOfPhotos($db, $num, $pos)
    {
        // recupere une partie des photos par ordre de creation descendant et les renvoie dans un tableau
        $statement = $db->prepare('SELECT id, image_name, user_id FROM Image ORDER BY creation_date DESC LIMIT :offset, :num');
        $statement->bindParam(':offset', $pos, PDO::PARAM_INT);
        $statement->bindParam(':num', $num, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetchAll();
        $statement->closeCursor();
        return $result;
    }

    public function countPhotos($db)
    {
        $sql = 'SELECT count(*) as total FROM Image';
        $count = $db->query($sql)->fetchColumn();
        return $count;
    }

    public function listPhotos($db)
    {
        // recupere les photos de l'utilisateur par ordre de creation descendant et les renvoie dans un tableau
        $statement = $db->prepare("SELECT id, image_name, user_id FROM Image WHERE user_id = :user_id ORDER BY creation_date DESC");
        $statement->bindParam(':user_id', $this->user_id);
        $statement->execute();
        $result = $statement->fetchAll();
        return $result;
    }

    public function likesPerPhoto($db)
    {
        // renvoie le nombre de likes pour une photo donnee
        // A CORRIGER : utiliser bindParam pour eviter une faille SQL dans la ligne ci-dessous !
        $sql = 'SELECT count(l.id) likes FROM Image i INNER JOIN like_table l ON i.id = l.image_id WHERE i.id='.$this->id;
        $count = $db->query($sql)->fetchColumn();
        return ($count);
    }

    public function persist($db)
    {
        $statement = $db->prepare('INSERT INTO Image
        (user_id, image_name)
        VALUES
        (:user_id, :image_name)');
        $statement->bindParam(':user_id', $this->user_id);
        $statement->bindParam(':image_name', $this->image_name);
        $statement->execute();
        return true;
    }

    public function delete($db)
    {
        $statement = $db->prepare('DELETE FROM Image WHERE user_id=:user_id AND image_name=:image_name');
        $statement->bindParam(':user_id', $this->user_id);
        $statement->bindParam(':image_name', $this->image_name);
        $statement->execute();
        return true;
    }
}
