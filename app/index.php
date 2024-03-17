<?php
include 'db.php';

// Traitement de l'ajout
if(isset($_POST['ajouter'])){
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $sujet = $_POST['sujet'];
    
    $sql = "INSERT INTO users (NOM, PRENOM, EMAIL, SUJET) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$nom, $prenom, $email, $sujet]);
    
    header("Location: index.php");
}

// Traitement de la modification
if(isset($_POST['modifier'])){
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $sujet = $_POST['sujet'];
    
    $sql = "UPDATE users SET NOM=?, PRENOM=?, EMAIL=?, SUJET=? WHERE ID=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$nom, $prenom, $email, $sujet, $id]);
    
    header("Location: index.php");
}

// Traitement de la suppression
if(isset($_GET['supprimer'])){
    $id = $_GET['supprimer'];
    
    $sql = "DELETE FROM users WHERE ID=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Application</title>
</head>
<body>
    <h1>Liste des utilisateurs</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Sujet</th>
            <th>Actions</th>
        </tr>
        <?php
        $sql = "SELECT * FROM users";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($users) {
            foreach($users as $user) {
                echo "<tr>";
                echo "<td>".$user['ID']."</td>";
                echo "<td>".$user['NOM']."</td>";
                echo "<td>".$user['PRENOM']."</td>";
                echo "<td>".$user['EMAIL']."</td>";
                echo "<td>".$user['SUJET']."</td>";
                echo "<td>
                        <a href='?modifier=".$user['ID']."'>Modifier</a> |
                        <a href='?supprimer=".$user['ID']."'>Supprimer</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>Aucun enregistrement trouvé.</td></tr>";
        }
        ?>
    </table>
    
    <h2>Ajouter un utilisateur</h2>
    <form action="" method="post">
        Nom: <input type="text" name="nom"><br>
        Prénom: <input type="text" name="prenom"><br>
        Email: <input type="email" name="email"><br>
        Sujet: <input type="text" name="sujet"><br>
        <input type="submit" name="ajouter" value="Ajouter">
    </form>
    
    <?php
    // Formulaire de modification
    if(isset($_GET['modifier'])){
        $id = $_GET['modifier'];
        $sql = "SELECT * FROM users WHERE ID=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
        <h2>Modifier un utilisateur</h2>
        <form action="" method="post">
            <input type="hidden" name="id" value="<?php echo $user['ID']; ?>">
            Nom: <input type="text" name="nom" value="<?php echo $user['NOM']; ?>"><br>
            Prénom: <input type="text" name="prenom" value="<?php echo $user['PRENOM']; ?>"><br>
            Email: <input type="email" name="email" value="<?php echo $user['EMAIL']; ?>"><br>
            Sujet: <input type="text" name="sujet" value="<?php echo $user['SUJET']; ?>"><br>
            <input type="submit" name="modifier" value="Modifier">
        </form>
    <?php } ?>
</body>
</html>