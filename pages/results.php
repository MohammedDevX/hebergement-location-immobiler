<?php 
session_start();

require "../includes/connection.php";

$search = $_POST["results"];
$query = "SELECT *
			FROM annonce INNER JOIN photos ON annonce.id_annonce=photos.id_annonce
			INNER JOIN ville ON annonce.id_ville=ville.id_ville
			INNER JOIN hote ON annonce.id_hote=hote.id_hote
			INNER JOIN locataire ON hote.id_locataire=locataire.id_locataire
			WHERE annonce.titre LIKE '%$search%'";
$stmt = $conn->prepare($query);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="">
	<div class="navbar-container">
        <?php include "../includes/navbar.php" ?>
    </div>
	<div class="bg-white w-full font-[Grotesk]">
		<div class="mx-auto px-4 py-16 sm:px-6 sm:py-24 lg:max-w-8xl">
		  <h2 class="text-6xl pl-10  font-[Krylon]  tracking-tight text-gray-900 ">Search results : <?php echo $search ?></h2>
	  
		  <div class=" mt-11 grid px- grid-cols-1  gap-x-6 gap-y-10 sm:grid-cols-1 xl:grid-cols-4 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-5">
			<!-- Start Propr... -->
			<?php foreach ($data as $dt=>$img) { ?>
                <div class="group relative">
                    <img src="<?php echo $img["photo"] ?>" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300">
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">
                                <a href="detaille.php?id_annonce=<?php echo $img["id_annonce"] ?>">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    <?php echo $img["nom_ville"] ?>, Morocco
                                </a>
                            </h3>
                            <p class="mt-1 text-md text-gray-500">Hosted By <?php echo $img["nom"] ?> <?php echo $img["prenom"] ?></p>
                            <p class="text-sm font-bold text-gray-900">MAD <?php echo number_format($img["prix_nuit"], 0, ',', ',') ?> night</p>
                        </div>
                    </div>
                </div>
                <?php } ?>
		  </div>
		</div>
	  </div>

	<!-- Footer -->
	<div><?php include "../includes/footer.html" ?></div>
	<!-- Footer -->
	 <!-- <script src="../assets/js/script.js"></script> -->
</body>
</html>