<?php defined("C5_EXECUTE") or die("Access Denied.");

if (!empty($cv_items)) {
foreach ($cv_items as $cv_item_key => $cv_item) { ?>


<div class="tallacman_cv_wrapper">
	<div class="tcv-main">
		<div class="tcv-title">

			<div class="tcv-position">
        <?php
          if (isset($cv_item["position"]) && trim($cv_item["position"]) != "") {
            echo h($cv_item["position"]);
          } ?>
			</div><!--   emd position  -->

			<div class="tcv-employer">
        <?php
        if (isset($cv_item["employer"]) && trim($cv_item["employer"]) != "") {
        echo h($cv_item["employer"]);
        } ?>
			</div><!--   end employer  -->

		</div><!--   end title  -->



		<div class="tcv-summary">
      <?php
      if (isset($cv_item["summary"]) && trim($cv_item["summary"]) != "") {
      echo $cv_item["summary"];
      } ?>
		</div>
	</div> <!--   end  main -->

	<div class="tcv-sidebar">
		<div class="tcv-dates">
			<span class=tcv-start-date>
				<?php if (isset($cv_item["dateStart"]) && $cv_item["dateStart"] > 0) {
					echo strftime("%b %Y",$cv_item["dateStart"]);
				} ?>
			</span>
			<span class="tcv-spacer">&mdash;</span>
			<span class=tcv-end-date>
				<?php if (isset($cv_item["dateEnd"]) && $cv_item["dateEnd"] > 0) {
					echo strftime("%b %Y",$cv_item["dateEnd"]);
				} ?>
			</span>
		</div>
	</div>

</div> <!--   end tallacman_cv_wrapper  -->


<?php } ?>
<?php } ?>
