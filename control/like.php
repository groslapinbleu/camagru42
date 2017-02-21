<?PHP
if(!isset($_SESSION))
{
	session_start();
}
require __DIR__ . '/../config/database.php';
require __DIR__ . '/../model/Like.class.php';
require __DIR__ . '/../model/DBAccess.class.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$image_id = $_POST['image_id'];
	$login = $_SESSION['logged_on_user'];
	if (empty($image_id))
	{
		echo "ERREUR : le champs image_id doit etre rempli !";
		return;
	}
	else if (empty($login))
	{
		echo "ERREUR : pas d'utilisateur connecté";
	}
	else
	{
		$data = array(
					'liker_id' => $login,
					'image_id' => $image_id
					);
		$like = new Like($data);
		$db = new DBAccess($DB_DSN, $DB_USER, $DB_PASSWORD);
		if (!$like->exists($db->db))
		{
			if (!$like->persist($db->db))
			{
				echo "ERREUR : probleme d'insertion en base";
				return;
			}
		}
		else
			echo "Like reussi";
		return;
	}
}
echo 'Erreur de PHP quelque part...';
return;
?>
