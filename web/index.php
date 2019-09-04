<?php declare(strict_types=1);

require_once '../vendor/autoload.php';

use DataDog\DogStatsd;
use DeviceDetector\DeviceDetector;

class App
{
	public const APPLE   = 'Apple';
	public const ANDROID = 'Android';
	public const ALL     = [self::ANDROID, self::APPLE];

	/** @var PDO */
	private $pdo;

	/** @var DogStatsd */
	private $statsd;

	public function __construct()
	{

		$createTable = <<<'SQL'
CREATE TABLE IF NOT EXISTS `clicks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `brand` varchar(1000) NOT NULL,
  `choice` char(7) NOT NULL,
  `thetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL;

		$this->pdo    = new PDO('mysql:host=mysql;dbname=todo', 'root', '');
		$this->pdo->exec($createTable);

		$this->statsd = new DogStatsd(['host' => 'telegraf']);
	}

	public function saveClick(string $choice, string $userAgent)
	{
		$this->statsd->increment('api.calls.vote', 1, ['choice' => $choice, 'brand' => 'test']);
		echo "click saved\n";
		$deviceDetector = new DeviceDetector($userAgent);
		$deviceDetector->parse();

		if ($deviceDetector->isMobile())
		{
			$brand = $deviceDetector->getBrandName();
			if ($brand !== self::APPLE)
			{
				$brand = self::ANDROID;
			}
		} else
		{
			echo 'not saved, because ua is not mobile: ' . $userAgent;

			return;
		}

		if (!in_array($choice, self::ALL, true))
		{
			$choice = 'bad';
		}

		$this->persist($choice, $brand);
	}

	private function persist(string $choice, string $brand)
	{
		$this->statsd->increment('vote', 1, ['choice' => $choice, 'brand' => $brand]);
		//$this->statsd->gauge('vote', 1);

		$statement = $this->pdo->prepare('INSERT INTO clicks(brand, choice) values(:brand, :choice)');
		$statement->execute(
			[
				'brand'  => $brand,
				'choice' => $choice,
			]
		);
	}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
	$app = new App();

	$app->saveClick($_POST['choice'] ?? 'not set', $_SERVER['HTTP_USER_AGENT']);

	echo "\nok\n";
	exit;
}

?>


<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
		  integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

	<title>Deeper PHP Luxembourg</title>

	<style>

		div.shell img {
			width: 100px;
			height: 100px;
			margin: 30px 5px;
			padding: 4px 4px;
			opacity: 1;

		}

		div.shell {
			/*border: 1px solid darkgray;*/
		}

		div.shell:hover {
			background-color: black;
			cursor: pointer;
		}


		div.col {
			text-align: center;
		}

		#spinner {
			padding: 10px;
			width: 100%;
			height: 95px;
		}

	</style>
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col">
			<div id="spinner"><span>ready</span><br><img style="display: none;" height="80" width="80"
														 src="spinner.svg"/></div>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<div data-choice="Android" class="shell">
				<img class="fluid" src="android.png">
			</div>
		</div>
		<div class="col">
			<div data-choice="Apple" class="shell">
				<img class="fluid" src="apple.png">
			</div>
		</div>
	</div>
</div>


<script src="https://code.jquery.com/jquery-3.2.1.min.js"
		crossorigin="anonymous"></script>
<script>

	$(
		function () {

			$('div.shell').on('click', function (e) {

					var $span = $('#spinner span');
					var $spinner = $('#spinner img');

					$span.text('Loading...');
					$spinner.show();

					var choice = $(this).data('choice');
					$.post('/', {'choice' : choice}, function () {
						console.log('saved ' + choice);

						$span.text('ready');
						$spinner.hide();

					});

					var $div = $(this);
					$div.animate({'opacity' : 0}, 500, 'swing', function () {
						$(this).css({'opacity' : 1, 'background-color' : 'inherit'})
					});
				}
			)
		}
	);

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
		integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
		crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
		integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
		crossorigin="anonymous"></script>
</body>
</html>
