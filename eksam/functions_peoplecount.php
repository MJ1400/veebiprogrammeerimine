<?php
  // käivitame sessiooni
  // session_start();
  // var_dump($_SESSION);

  function storePeopleCount($entrants, $leavers, $gender, $occupation, $totalcount) {
    $notice = null;
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT totalcount FROM vppeoplecount WHERE gender=? and occupation=?");
      $stmt->bind_param("ii", $gender, $occupation);
      $stmt->bind_result($totalCountFromDb);
      if($stmt->execute()){
        if($leavers > $totalCountFromDb and $totalCountFromDb != null and $leavers != $totalCountFromDb and $leavers > null){
          $notice = "Lahkuvate inimeste arv ei saa olla suurem kui praegu olevate inimeste arv hoones!";
        } else {
          $stmt->close();
          $stmt = $conn->prepare("INSERT INTO vppeoplecount (userid, entrants, leavers, gender, occupation, totalcount) VALUES (?, ?, ?, ?, ?, ?)");
          $stmt->bind_param("iiiiii", $_SESSION["userID"], $entrants, $leavers, $gender, $occupation, $totalcount);
          if($stmt->execute()) {
            $notice = "Inimeste arvu salvestamine õnnestus!";
          } else {
            $notice = "Inimeste arvu salvestamisel tekkis tehniline viga: " .$stmt->error;
          }
        }
      }



    $stmt->close();
    $conn->close();
    return $notice;
  }

  function readAllPeopleCount() {
    $notice = null;
    $maxPeopleCount = null;
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    // $stmt = $conn->prepare("SELECT message, created FROM vpmsg");
    $stmt = $conn->prepare("SELECT SUM(totalcount) FROM vppeoplecount");
    echo $conn->error;
    //mis sealt tuleb?
    $stmt->bind_result($allPeopleCountFromDb);
    $stmt->execute();
    if($stmt->fetch()) {
      $allPeopleCount = $allPeopleCountFromDb;

    } else{
      $allPeopleCount = "Inimeste koguarvu lugemisel hoones tekkis tehniline viga!";
    }
    if(empty($allPeopleCountFromDb)) {
      $allPeopleCount = "0";
    }

    $stmt->close();
    $conn->close();
    return $allPeopleCount;
  }

  function readPeopleCount($gender, $occupation) {
    $notice = null;
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    // $stmt = $conn->prepare("SELECT message, created FROM vpmsg");
    $stmt = $conn->prepare("SELECT SUM(totalcount) FROM vppeoplecount WHERE gender=? and occupation=? ");
    echo $conn->error;
    $stmt->bind_param("ii", $gender, $occupation);
    //mis sealt tuleb?
    $stmt->bind_result($PeopleCountFromDb);
    $stmt->execute();
    if($stmt->fetch()) {
      $PeopleCount = $PeopleCountFromDb;

    } else{
      $PeopleCount = "Tekkis tehniline viga!";
    }
    if(empty($PeopleCountFromDb)) {
      $PeopleCount = "0";
    }

    $stmt->close();
    $conn->close();
    return $PeopleCount;
  }





 ?>
