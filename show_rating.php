<?php
include "./includes/header.php";
?>
    <title>Reviews</title>
    <script src="rating.js"></script>
    <link rel="stylesheet" href="Public/CSS/style.css">
    <div class="container">
        <h2>Reviews</h2>
        <?php
        include "Rating.php";
        //Check if user is logged in with session
        if (isset($_SESSION["User"]['LogonName'])) {
            //Check if user has already rated the product
    $rating = new Rating();
        print $_SESSION['User']["LogonName"];
        $itemDetails = $rating->getItem($_GET['item_id']);
        foreach($itemDetails as $item){
        $average = $rating->getRatingAverage($item["StockItemID"]);
        ?>
        <div class="row">
        </div>
        <div class="col-sm-4">
            <div><span class="average"><?php printf('%.1f', $average); ?> <small>/ 5</small></span> <span class="rating-reviews"><a href="show_rating.php?item_id=<?php print $_GET['item_id']; ?>">Rating & Reviews</a></span></div>
        </div>
    </div>
<?php } ?>

<?php
$itemRating = $rating->getItemRating($_GET['item_id']);
$ratingNumber = 0;
$count = 0;
$fiveStarRating = 0;
$fourStarRating = 0;
$threeStarRating = 0;
$twoStarRating = 0;
$oneStarRating = 0;
foreach($itemRating as $rate){
    $ratingNumber+= $rate['ratingNumber'];
    $count += 1;
    if($rate['ratingNumber'] == 5) {
        $fiveStarRating +=1;
    } else if($rate['ratingNumber'] == 4) {
        $fourStarRating +=1;
    } else if($rate['ratingNumber'] == 3) {
        $threeStarRating +=1;
    } else if($rate['ratingNumber'] == 2) {
        $twoStarRating +=1;
    } else if($rate['ratingNumber'] == 1) {
        $oneStarRating +=1;
    }
}
$average = 0;
if($ratingNumber && $count) {
    $average = $ratingNumber/$count;
}
?>
    <br>
    <div id="ratingDetails">
        <div class="row">
            <div class="col-sm-3">
                <h4>Rating and Reviews</h4>
                <h2 class="bold padding-bottom-7"><?php printf('%.1f', $average); ?> <small>/ 5</small></h2>
                <?php
                $averageRating = round($average, 0);
                for ($i = 1; $i <= 5; $i++) {
                    $ratingClass = "btn-default btn-grey";
                    if($i <= $averageRating) {
                        $ratingClass = "btn-warning";
                    }
                    ?>
                    <button type="button" class="btn btn-sm <?php echo $ratingClass; ?>" aria-label="Left Align">
                        <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                    </button>
                <?php } ?>
            </div>
            <div class="col-sm-3">
                <?php
                $fiveStarRatingPercent = round(($fiveStarRating/5)*100);
                $fiveStarRatingPercent = !empty($fiveStarRatingPercent)?$fiveStarRatingPercent.'%':'0%';

                $fourStarRatingPercent = round(($fourStarRating/5)*100);
                $fourStarRatingPercent = !empty($fourStarRatingPercent)?$fourStarRatingPercent.'%':'0%';

                $threeStarRatingPercent = round(($threeStarRating/5)*100);
                $threeStarRatingPercent = !empty($threeStarRatingPercent)?$threeStarRatingPercent.'%':'0%';

                $twoStarRatingPercent = round(($twoStarRating/5)*100);
                $twoStarRatingPercent = !empty($twoStarRatingPercent)?$twoStarRatingPercent.'%':'0%';

                $oneStarRatingPercent = round(($oneStarRating/5)*100);
                $oneStarRatingPercent = !empty($oneStarRatingPercent)?$oneStarRatingPercent.'%':'0%';

                ?>
                <div class="pull-left">
                    <div class="pull-left" style="width:35px; line-height:1;">
                        <div style="height:9px; margin:5px 0;">5 <span class="glyphicon glyphicon-star"></span></div>
                    </div>
                    <div class="pull-left" style="width:180px;">
                        <div class="progress" style="height:9px; margin:8px 0;">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="5" style="width: <?php echo $fiveStarRatingPercent; ?>">
                                <span class="sr-only"><?php echo $fiveStarRatingPercent; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="pull-right" style="margin-left:10px;"><?php echo $fiveStarRating; ?></div>
                </div>

                <div class="pull-left">
                    <div class="pull-left" style="width:35px; line-height:1;">
                        <div style="height:9px; margin:5px 0;">4 <span class="glyphicon glyphicon-star"></span></div>
                    </div>
                    <div class="pull-left" style="width:180px;">
                        <div class="progress" style="height:9px; margin:8px 0;">
                            <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="4" aria-valuemin="0" aria-valuemax="5" style="width: <?php echo $fourStarRatingPercent; ?>">
                                <span class="sr-only"><?php echo $fourStarRatingPercent; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="pull-right" style="margin-left:10px;"><?php echo $fourStarRating; ?></div>
                </div>
                <div class="pull-left">
                    <div class="pull-left" style="width:35px; line-height:1;">
                        <div style="height:9px; margin:5px 0;">3 <span class="glyphicon glyphicon-star"></span></div>
                    </div>
                    <div class="pull-left" style="width:180px;">
                        <div class="progress" style="height:9px; margin:8px 0;">
                            <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="3" aria-valuemin="0" aria-valuemax="5" style="width: <?php echo $threeStarRatingPercent; ?>">
                                <span class="sr-only"><?php echo $threeStarRatingPercent; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="pull-right" style="margin-left:10px;"><?php echo $threeStarRating; ?></div>
                </div>
                <div class="pull-left">
                    <div class="pull-left" style="width:35px; line-height:1;">
                        <div style="height:9px; margin:5px 0;">2 <span class="glyphicon glyphicon-star"></span></div>
                    </div>
                    <div class="pull-left" style="width:180px;">
                        <div class="progress" style="height:9px; margin:8px 0;">
                            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="5" style="width: <?php echo $twoStarRatingPercent; ?>">
                                <span class="sr-only"><?php echo $twoStarRatingPercent; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="pull-right" style="margin-left:10px;"><?php echo $twoStarRating; ?></div>
                </div>
                <div class="pull-left">
                    <div class="pull-left" style="width:35px; line-height:1;">
                        <div style="height:9px; margin:5px 0;">1 <span class="glyphicon glyphicon-star"></span></div>
                    </div>
                    <div class="pull-left" style="width:180px;">
                        <div class="progress" style="height:9px; margin:8px 0;">
                            <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="5" style="width: <?php echo $oneStarRatingPercent; ?>">
                                <span class="sr-only"><?php echo $oneStarRatingPercent; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="pull-right" style="margin-left:10px;"><?php echo $oneStarRating; ?></div>
                </div>
            </div>
            <div class="col-sm-3">
                <button type="button" id="rateProduct" class="btn btn-info <?php if(!empty($_SESSION['userid']) && $_SESSION['userid']){ echo 'login';} ?>">Review dit product</button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-7">
                <hr/>
                <div class="review-block">
                    <?php
                    print $_SESSION["User"]["LogonName"];
                    $itemRating = $rating->getItemRating($_GET['item_id']);
                    foreach($itemRating as $rating){
                        $date=date_create($rating['created']);
                        $reviewDate = date_format($date,"M d, Y");
                        ?>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="review-block-name">By <a href="#"><?php echo $rating['LogonName']; ?></a></div>
                                <div class="review-block-date"><?php echo $reviewDate; ?></div>
                            </div>
                            <div class="col-sm-9">
                                <div class="review-block-rate">
                                    <?php
                                    for ($i = 1; $i <= 5; $i++) {
                                        $ratingClass = "btn-default btn-grey";
                                        if($i <= $rating['ratingNumber']) {
                                            $ratingClass = "btn-warning";
                                        }
                                        ?>
                                        <button type="button" class="btn btn-xs <?php echo $ratingClass; ?>" aria-label="Left Align">
                                            <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                        </button>
                                    <?php } ?>
                                </div>
                                <div class="review-block-title"><?php echo $rating['title']; ?></div>
                                <div class="review-block-description"><?php echo $rating['comments']; ?></div>
                            </div>
                        </div>
                        <hr/>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div id="ratingSection" style="display:none;">
        <div class="row">
            <div class="col-sm-12">
                <form id="ratingForm" method="POST">
                    <div class="form-group">
                        <h4>Review dit product</h4>
                        <button type="button" class="btn btn-warning btn-sm rateButton" aria-label="Left Align">
                            <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-default btn-grey btn-sm rateButton" aria-label="Left Align">
                            <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-default btn-grey btn-sm rateButton" aria-label="Left Align">
                            <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-default btn-grey btn-sm rateButton" aria-label="Left Align">
                            <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-default btn-grey btn-sm rateButton" aria-label="Left Align">
                            <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                        </button>
                        <input type="hidden" class="form-control" id="rating" name="rating" value="1">
                        <input type="hidden" class="form-control" id="itemId" name="itemId" value="<?php echo $_GET['item_id']; ?>">
                        <input type="hidden" name="action" value="saveRating">
                    </div>
                    <div class="form-group">
                        <label for="usr">Titel*</label>
                        <input type="text" class="form-control" id="title" name="Titel" required>
                    </div>
                    <div class="form-group">
                        <label for="comment">Comment*</label>
                        <textarea class="form-control" rows="5" id="comment" name="comment" required></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-info" id="saveReview">Review opslaan</button> <button type="button" class="btn btn-info" id="cancelReview">Annuleer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
<?php include("includes/footer.php");
print $_SESSION["User"]["LogonName"];
}
?>