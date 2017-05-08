<?php
require_once('app/init.php');

try {
    $handler = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die('Oops, we encountered an issue, please try again later.');
}

$ailments = array();
$query = $handler->query("SELECT * FROM smartmedic_ailments;");
while($r = $query->fetch()) {
    $ailments[$r['id']] = $r['name'];
}

// echo '<pre>';
// print_r($ailments);
// echo '</pre>';

$symptoms = array();
$query = $handler->query("SELECT * FROM smartmedic_symptoms;");
while($r = $query->fetch()) {
    $symptoms[$r['ailment']][] = $r['symptom'];
}

// echo '<pre>';
// print_r($symptoms);
// echo '</pre>';

// foreach($symptoms as $key => $symptom) {
//     if(!in_array('stomach', $symptom)) {
//         unset($symptoms[$key]);
//     }
// }

// echo '<pre>';
// print_r($symptoms);
// echo '</pre>';
?>

<!doctype html>

<html>
    <head>
        <link href="css/app.css" type="text/css" rel="stylesheet">
        <link href="css/symptoms.css" type="text/css" rel="stylesheet">

        <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700" rel="stylesheet">
        <title>Smart Medic</title>
    </head>

    <body style="overflow: hidden;">
        <a href="index" class="reset">< Start Over</a>

        <div class="window">
            <div class="main-container cf" style="display: block;">
                <p class="question">Please click the body location(s) where you feel the worst:</p>

                <ul class="symptoms" id="three">
                    <li id="item1" onclick="flip('item1');">Head</li>
                    <li id="item2" onclick="flip('item2');">Chest</li>
                    <li id="item3" onclick="flip('item3');">Stomach</li>
                </ul>

                <ul class="symptoms" id="four" style="display: none;">
                    <li id="item4" onclick="flip('item4');"></li>
                    <li id="item5" onclick="flip('item5');"></li>
                    <li id="item6" onclick="flip('item6');"></li>
                    <li id="item7" onclick="flip('item7');"></li>
                </ul>

                <div class="next">
                    <a href="#" onclick="next();">Next ></a>
                </div>
            </div>
        </div>

        <div class="window" id="complete" style="transform: translateX(100%)">
            <div class="main-container">
                <p class="subtitle" id="complete-input" style="margin-bottom: 20px;">Based on your input, we believe you have:</p>
                <p class="title" id="complete-title">[...]</p>
                <br>
                <p class="question" id="complete-question">[...]</p>
                <p class="subtitle"></p>
                <p class="subtitle"></p>
                <br><br>
                <center class="button">
                    <a href="update">Add/Update Ailments</a>
                </center>
            </div>

        <script>
            var test = ['Head', 'Chest', 'Stomach'];
            var tested = ['Head', 'Chest', 'Stomach'];
            //console.log(tested);

            var ailments = <?php echo json_encode($ailments); ?>;
            //console.log(ailments);

            var symptoms = <?php echo json_encode($symptoms); ?>;
            console.log(symptoms);

            function flip(id) {
                var x = document.getElementById(id);
                if(x.classList != 'selected') {
                    //SELECTED
                    x.className = 'selected';
                } else {
                    //NOT
                    x.className = '';
                }
            }

            function fail() {
                console.log('Could not diagnose.');
                document.getElementById('complete').style = '';
                document.getElementById('complete-input').style = 'display: none;';
                document.getElementById('complete-title').innerHTML = 'Could not diagnose...';
                document.getElementById('complete-question').innerHTML = 'If you would like to better our system so we can diagnose better next time, click below.';
                return;
            }

            function next() {
                var x = document.getElementsByClassName('selected');

                /* if user hits next on the first screen, fail */
                if(x.length == 0 && tested.length == 3) {
                    return;
                }

                /* if user hits next and there is nothing left to test, fail */
                if(test.length < 1) {
                    fail();
                    return;
                }

                /* print all the items that were selected */
                for(var i = 0; i < x.length; i++) {
                    //console.log(x[i].innerHTML); //print the items that are to be found and kept
                }

                /* set size of array */
                var keys = Object.keys(symptoms);
                console.log(keys);

                /* after user has selected the ailments that apply, remove the rows that don't apply */
                for(var i = 0; i < keys.length; i++) { //for the keys in the symptoms
                    for(var j = 0; j < x.length; j++) { //for the items selected
                        if(symptoms[keys[i]] != null) {
                        // if(symptoms[keys[i]] == null) {
                        //     if(Object.keys(symptoms) < 1) {
                        //         fail();
                        //         return;
                        //     }
                        // } else {
                            if(symptoms[keys[i]].indexOf(x[j].innerHTML) == -1) {
                                delete symptoms[keys[i]];
                            }
                        }

                    }

                    //maybe???
                    if(Object.keys(symptoms) < 1) {
                        fail();
                        return;
                    }
                }

                /* update size of array */
                var keys = Object.keys(symptoms);
                //console.log(keys);

                /* possible, remaining options */
                console.log('Possible remaining options:');
                var options = [];
                for(var i = 0; i < keys.length; i++) {
                    options.push(ailments[keys[i]]);
                }
                console.log(options);

                /* if keys has only one item, give answer!! */
                if(keys.length == 1) {
                    console.log(ailments[keys[0]]);
                    document.getElementById('complete-title').innerHTML = ailments[keys[0]];
                    document.getElementById('complete-question').innerHTML = 'If you believe you have been incorrectly diagnosed, click below.';
                    document.getElementById('complete').style = '';
                    return;
                }

                /* add all remaining array items to a super array */
                var superarr = [];
                for(var i = 0; i < keys.length; i++) { //for the keys in the symptoms
                    for(var j = 0; j < symptoms[keys[i]].length; j++) {
                        superarr.push(symptoms[keys[i]][j]);
                    }
                }

                /* sort super array */
                superarr.sort();
                //console.log(superarr);

                /* group same items */
                var superout = [];
                for(var i = 0; i < superarr.length; i++) {
                    if(!superout[superout.length - 1] || superout[superout.length - 1].value != superarr[i]) {
                        superout.push({value: superarr[i], times: 1});
                    } else {
                        superout[superout.length - 1].times++;
                    }
                }

                //console.log(superout);

                /* sort the superout list from smallest number of uses, to biggest */
                test = [];
                for(var count = 1; count <= keys.length; count++) { //for the smallest number of uses, add those to the list
                    for(var i = 0; i < superout.length; i++) { //for all the items remaining
                        if(superout[i]['times'] == count &&  tested.indexOf(superout[i]['value']) == -1) { //if equals the current count and wasn't already tested
                            if(test.length < 4) {
                                test.push(superout[i]['value']);
                                tested.push(superout[i]['value']);
                            }
                        }
                    }
                }

                /* for all elements with 'selected' - mark as not */
                while(x.length != 0) {
                    x[0].className = '';
                }

                //console.log(test);
                //console.log(tested);

                var three = document.getElementById('three');
                three.style.display = 'none';

                var four = document.getElementById('four');
                four.style.display = 'block';

                items = ['item4', 'item5', 'item6', 'item7']
                for(var i = 0; i < test.length; i++) {
                    document.getElementById(items[i]).innerHTML = test[i];
                }
            }
        </script>
    </body>
</html>
