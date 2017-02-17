<?PHP
session_start();
require __DIR__ . '/../config/database.php';
require __DIR__ . '/../model/Image.class.php';
require __DIR__ . '/../model/DBAccess.class.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$image_name = $_POST['image_name'];
	$login = $_SESSION['logged_on_user'];
	if (empty($image_name))
	{
		echo "ERREUR : le champ image_name doit etre rempli !";
		return;
	}
	else if (empty($login))
	{
		echo "ERREUR : pas d'utilisateur connecté";
	}
	else
	{
		$dir = $_SERVER['DOCUMENT_ROOT'].'/camagru/data/';
		$file = basename($image_name); // attention, verifier que ca marche sous windows 
		$filename = $dir.$file;
		$data = array(
					'user_id' => $login,
					'image_name' => $file
		);
		$image = new Image($data);
		$db = new DBAccess($DB_DSN, $DB_USER, $DB_PASSWORD);
		if (!$image->delete($db->db))
			echo "ERREUR : probleme de suppression en base";
		// header('location:/camagru/view/montage.php');
		return;
	}
}
echo 'Erreur de PHP quelque part...';
return;
?>