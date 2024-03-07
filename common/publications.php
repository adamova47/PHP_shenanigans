<h2>Publications of <?php echo $user_full_name; ?></h2>

<div class="publ">
    <ul>
        <?php
        //id`, `ptype`, `address`, `author`, `booktitle`, `edition`, `editor`, `institution`, `journal`, `pub_key`, `month`, `note`, `number`, `organization`, `pages`, `publisher`, `school`, `series`, `title`, `volume`, `year`

	    $mysqli = db_connect();
        $query = 'SELECT * FROM ' . $publiTable . ' AS p, ' . $userxpub . ' AS rel WHERE id = rel.publication AND rel.user = ' . $uid . ' ORDER BY p.year DESC';
        $res = mysqli_query($mysqli, $query);
        $data = array();
        if ($res) {
            $c = 0;
            while ($row = mysqli_fetch_array($res)) {
                if ($row['vis'] == 1) {
                    $data[$c] = $row;
                    ?>
                    <li id="pub<?= $row['id'] ?>"><?= publToString($row); ?></li>
                    <?php
                    $c++;
                }
            }
            if ($c > 12)
                echo '<p class="top"><a href="#header">to the top</a></p>';
        }

        mysqli_close($mysqli);
        ?>
    </ul>
</div>
