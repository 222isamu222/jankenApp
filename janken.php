<!-- 
やること
・CPUの手の画像表示
・なんか戦績表示がうまくいってないからそこの修正 -->

<?php
ini_set('display_errors', 1);  // エラー表示を有効
ini_set('error_reporting', E_ALL);  // エラー出力の範囲を指定
ini_set('error_log', 'error.log');  // エラーログの保存先

/*initiate process */
$GLOBALS["cpuHandImage"] = "paper.jpg";
$GLOBALS["csvFile"] = "result.csv";
$GLOBALS["image_template"] = '<img src="../asset/'.$GLOBALS["cpuHandImage"].'">';

if(!file_exists($GLOBALS["csvFile"])){
    $csv = fopen($GLOBALS["csvFile"], "w");
    fwrite($csv, 0 . "," . 0 . "," . 0);  //win,lose,draw
    fclose($csv);
}

$csv = fopen($GLOBALS["csvFile"] , 'r+');
$csvData = fgetcsv($csv);
$GLOBALS["str"] = $csvData[0] . "勝  " . $csvData[1] . "敗  " . $csvData[2] . "引き分け";
fclose($csv);



if(isset($_POST['paper'])){
    janken(2);
}
if(isset($_POST['rock'])){
    janken(0);
}
if(isset($_POST['scissor'])){
    janken(1);
}

/*janken process */
function janken($data){
    $hand = $data;

    $randomNumber = rand(0,99);
    $cpuHand = null;
    if($randomNumber % 3 == 0){
        $cpuHand = 2;
        $GLOBALS["cpuHandImage"] = "paper.jpg";
    }
    if($randomNumber % 3 == 1){
        $cpuHand = 0;
        $GLOBALS["cpuHandImage"] = "rock.jpg";
    }
    if($randomNumber % 3 == 2){
        $cpuHand = 1;
        $GLOBALS["cpuHandImage"] = "scissor.jpg";
    }

    /*write file */
    $csv = fopen($GLOBALS["csvFile"] , 'r+');
    $csvData = fgetcsv($csv);

    $resultNum = ($hand - $cpuHand + 3) % 3;
    if($resultNum == 0){ $csvData[2]++; }
    if($resultNum == 1){ $csvData[1]++; }
    if($resultNum == 2){ $csvData[0]++; }

    rewind($csv);
    fputcsv($csv, $csvData);
    fclose($csv);

    $GLOBALS["str"] = $csvData[0] . "勝  " . $csvData[1] . "敗  " . $csvData[2] . "引き分け";
    $GLOBALS["image_template"] = '<img src="../asset/'.$GLOBALS["cpuHandImage"].'">';

}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel = "stylesheet" href = "style.css" />
    <link rel = "stylesheet" href = "button.css" />
</head>
<body>
    <div class="bg_pattern Polka"></div>

    <div class = "container">
        <div class = "top">
            <div class = "title">
                <p>じゃんけん</p>
            </div>
            <div class = "result">
                <p>あなたの戦績は<br>
                <?php
                    print $GLOBALS["str"];
                ?>
                <br>です.</p>
            </div>
        </div>

        <div class = "content">
            <div class = "cpu">
                <p>CPU</p>
                <div class = "hand">
                    <?php echo $GLOBALS["image_template"]?>
                </div>
            </div>

            <form action="" method="POST">
                <div class = "myself">
                    <div class = "hand">
                        <div id="paper" class="paper" name="paper" >
                            <button class="custom-btn btn-5" type="submit" name="paper"><span>Paper</span></button>
                            <img src = "../asset/paper.jpg" />
                        </div>

                        <div class = "rock">
                            <button class="custom-btn btn-6" type="submit" name="rock"><span>Rock</span></button>
                            <img src = "../asset/rock.jpg" />
                        </div>

                        <div class = "scissor">
                            <button class="custom-btn btn-8" type="submit" name="scissor"><span>Scissor</span></button>
                            <img src = "../asset/scissor.jpg" />
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</body>
</html>