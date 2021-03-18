<?php

if (isset($_POST)) {
    if (isset($_POST['back'])) {
        $backURL = explode('/', $_GET['toto']);
        array_pop($backURL);
        setcookie("path", $_GET['toto']);
        header('location: http://localhost/' . implode("/", $backURL));
    } elseif (isset($_POST['next'])) {
        header('location: http://localhost/' . $_COOKIE['path']);
        echo $_COOKIE['path'];
    }
}

function sortBySize($a, $b)
{
    if ($a['size'] == $b['size']) return 0;
    return ($a['size'] < $b['size']) ? 1 : -1;
}
function sortByName($a, $b)
{
    if ($a['name'] == $b['name']) return 0;
    return ($a['name'] < $b['name']) ? -1 : 1;
}
function sortByMTime($a, $b)
{
    if ($a['mtime'] == $b['mtime']) return 0;
    return ($a['mtime'] < $b['mtime']) ? 1 : -1;
}


function scan($path = NULL)
{
    $content = glob('/' . $path . '/' . '*');


    $files = [];

    foreach ($content as $value) {
        $files[] = [
            "path" => $value,
            "name" =>  end(explode("/", $value)),
            "mtime" => filemtime($value),
            "mtime_formated" => date('F d Y H:i:s.', filemtime($value)),
            "size" => filesize($value),
        ];
    }


    if (isset($_GET['sort'])) {
        if ($_GET['sort'] == 'nom') {
            usort($files, "sortByName");
        } elseif ($_GET['sort'] == 'dateModif') {
            usort($files, "sortByMTime");
        } elseif ($_GET['sort'] == "taille") {
            usort($files, "sortBySize");
        }
    }

    foreach ($files as $v) {


        if (is_dir("/" . $v['path'])) {
            echo '<ul style="list-style-type:none;"><li style="text-align:center;"><img src="/folder.jpg" width="30"><a href="' . $v['path'] . '">' . $v['name'] . " " . '</a>' . '</li></ul>' . '<br>';
        } else {
            echo "<ul style='text-align:center;list-style-type:none;'><li><img src='/file.jpg' width='30'>" . $v['name']  . " " . "<b>" . $v['size'] . ' bytes' .  "</b>" . '<br>' . 'Dernière modification : ' . $v['mtime_formated'] .  "</li></ul>" . "<br>";
        }
    }
}


?>

<?= '<h1 style="text-align:center;color:red;">Dossier actuel : ' . $_GET['toto'] . '</h1>' . '<br><br>'; ?>

<form method='get' action="">
    <select name="sort">
        <option value=''>--Trier par--</option>
        <option value='nom'>Nom</option>
        <option value='dateModif'>Date de dernière modification</option>
        <option value='taille'>Taille</option>
    </select>
    <input type='submit' name='submit' value='Valider'>
</form>

<form action="" method="post" style="text-align: center;">
    <input type="submit" name="back" value="Retour">
    <input type="submit" name="next" value="Avancer">
</form>
<?php

scan($_GET['toto']);
