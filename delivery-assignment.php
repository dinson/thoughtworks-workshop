<?php

/**
 * initial values
 */
$deliveryPartners = [
	0 => [
		'name' => 'Rahul',
		'distance' => 0,
	],
	1 => [
		'name' => 'Preetha',
		'distance' => 0,
	],
	2 => [
		'name' => 'Sanjay',
		'distance' => 0,
	],
	3 => [
		'name' => 'Violet',
		'distance' => 0,
	]

];
$selectedDeliveryPartner = false;

if (!empty($_POST)) :
	
	/**
	 * co-ordinates of the restaurant
	 */
	$restaurantX = $_POST['restaurantX'];
	$restaurantY = $_POST['restaurantY'];
	/**
	 * array containing co-ordinates of each delivery partner
	 */
	$deliveryPartnerX = $_POST['deliveryPartnerX'];
	$deliveryPartnerY = $_POST['deliveryPartnerY'];

	$deliveryPartnersDistance = [];

	/**
	 * create restaurant co-ordinates array to calculate euclidean distance
	 */
	$restaurantDistanceArray = [$restaurantX, $restaurantY];

	$tempArray = [];

	/**
	* loop thru delivery partners and calculate their distance from the restaurant
	*/
	foreach ($deliveryPartners as $key => $row) {
		/**
		 * create delivery partner co-ordinates array to calculate euclidean distance
		 */
		$deliveryPartnerDistanceArray = [$deliveryPartnerX[$key], $deliveryPartnerY[$key]];
		/**
		 * calculate distance between the restaurant and delivery partner
		 */
		$dis = calculateEuclideanDistance($restaurantDistanceArray, $deliveryPartnerDistanceArray);
		/**
		 * store calculated distance alongside delivery partner record to display
		 */
		$deliveryPartners[$key]['distance'] = $dis;
		/**
		 * create new array in key value format of "delivery partner" => "distance from restaurant"
		 */
		$tempArray[$row['name']] = $dis;
	}

	/**
	 * sort according to value to get the least distance record on top
	 */
	asort($tempArray);
	/**
	 * after sorting, get name of first delivery partner,
	 * because this person is at the least distance from the restaurant
	 */
	$selectedDeliveryPartner = key($tempArray);
	
endif;

/**
 * calculate euclidean distance between 2 co-ordinates
 * Formula:- d(a, b) = sqrt( summation{i=1,n}((b[i] - a[i]) ^ 2) )
 * @param array restaurant co-ordinates, array delivery partner co-ordinates
 * @return float distance - between restaurant and delivery partner
 */
function calculateEuclideanDistance($restaurantDistanceArray, $deliveryPartnerDistanceArray) {
	
	$sum = 0;

	foreach ($deliveryPartnerDistanceArray as $key => $value) {
		$diff = $restaurantDistanceArray[$key] - $deliveryPartnerDistanceArray[$key];
		$sq = pow($diff, 2);
		$sum += $sq;
	}

	$distance = sqrt($sum);

	return $distance;
}

?>

<form action="" method="post">

	<h1>Restaurant Co-ordinates</h1>
	<label>X</label>
	<input type="text" name="restaurantX" value="100">
	<label>Y</label>
	<input type="text" name="restaurantY" value="100">

	<h1>Delivery Partner Co-ordinates</h1>
	<?php foreach($deliveryPartners as $row) : ?>
		<label><?= $row['name'] ?> X</label>
		<input type="text" name="deliveryPartnerX[]">
		<label><?= $row['name'] ?> Y</label>
		<input type="text" name="deliveryPartnerY[]">
		<br>	
	<?php endforeach ?>

	<h1>Assignment</h1>
	
	<table>
		<thead>
			<tr>
				<th>#</th>
				<th>Delivery Partner</th>
				<th>Distance from Restaurant</th>
			</tr>
		</thead>
		<tbody>
			<?php $sno = 1; foreach($deliveryPartners as $row) : ?>
				<tr>
					<td><?= $sno++ ?></td>
					<td><?= $row['name'] ?></td>
					<td><?= $row['distance'] ?> Km</td>
				</tr>
				<!-- <input type="hidden" name="distance[<?= $row['distance'] ?>][<?= $row['name'] ?>]"> -->
			<?php endforeach ?>
		</tbody>
	</table>

	<div>
		<button type="submit">
			Pick delivery partner
		</button>
	</div>
</form>


<?php if ($selectedDeliveryPartner) : ?>
	<h1>Order is assigned to <?= $selectedDeliveryPartner ?></h1>
<?php endif ?>
