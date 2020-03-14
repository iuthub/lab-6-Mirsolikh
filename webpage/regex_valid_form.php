<?php

$pattern = "";
$text = "";
$replaceText = "";
$replacedText = "";
$containedWord = "";
$email_regex = "/[^.][a-z0-9A-Z]+@[a-z]+\.[a-z]{2,}/";
$phone_num_regex = "/\+998-[0-9]{2}-[0-9]{3}-[0-9]{4}/";
$parenthesis_regex = "/\[.+\]|\{.+\}|\(.+\)/";
$contains_word = "Not checked yet";
$match = "Not checked yet.";
$contains_email = "Not checked yet";
$contains_phone_number = "Not checked yet";
$text_without_whitespaces = "";
$text_without_nonnum_chars = "";
$text_without_newlines = "";
$parenthesis_stats = array();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$pattern = $_POST["pattern"];
	$text = $_POST["text"];
	$replaceText = $_POST["replaceText"];
	if (!empty($replaceText)) {
		$replacedText = preg_replace($pattern, $replaceText, $text);
	}
	$text_without_whitespaces = preg_replace("/\s/", "", $text);
	$text_without_nonnum_chars = preg_replace("/[^0-9\.\,]/", "", $text);
	$text_without_newlines = preg_replace("/\n/", "", $text);
	$containedWord = $_POST["contain"];
	preg_match_all($parenthesis_regex, $text, $parenthesis_stats);
	if (!empty($pattern)) {
		if (preg_match($pattern, $text)) {
			$match = "Match!";
		} else {
			$match = "Does not match!";
		}
	}
	if (preg_match("/$containedWord/", $text) and !empty($containedWord)) {
		$contains_word = "Yes";
	} else {
		$contains_word = "No";
	}
	if (preg_match($email_regex, $text)) {
		$contains_email = "Yes";
	} else {
		$contains_email = "No";
	}
	if (preg_match($phone_num_regex, $text)) {
		$contains_phone_number = "Yes";
	} else {
		$contains_phone_number = "No";
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Valid Form</title>
	<style media="screen">
		dd {
			color: red;
		}

		input[type="text"] {
			width: 40%;
		}

		textarea {
			overflow: wrap;
		}
	</style>
</head>

<body>
	<form action="regex_valid_form.php" method="post">
		<dl>
			<dt>Pattern</dt>
			<dd><input type="text" name="pattern" value="<?= $pattern ?>"></dd>

			<dt>Text</dt>
			<dd>
				<textarea name="text" rows="8" cols="80"><?= $text ?></textarea>
			</dd>

			<dt>Word you are looking for</dt>
			<dd><input type="text" name="contain" value="<?= $containedWord ?>"></dd>

			<dt>Replace Text</dt>
			<dd><input type="text" name="replaceText" value="<?= $replaceText ?>"></dd>

			<dt>Output Text</dt>
			<dd><?= $match ?></dd>

			<dt>Replaced Text</dt>
			<dd> <code><?= $replacedText ?></code></dd>

			<dt>Does contain needed word?</dt>
			<dd> <code><?= $contains_word ?></code></dd>

			<dt>Does contain an email?</dt>
			<dd> <code><?= $contains_email ?></code></dd>

			<dt>Does contain a phone number?</dt>
			<dd> <code><?= $contains_phone_number ?></code></dd>

			<dt>Text without white spaces</dt>
			<dd> <code><?= $text_without_whitespaces ?></code></dd>

			<dt>Text without non-numeric chars (except (.) and (,))</dt>
			<dd> <code><?= $text_without_nonnum_chars ?></code></dd>

			<dt>Text without new lines</dt>
			<dd> <code><?= $text_without_newlines ?></code></dd>

			<dt>Expressions inside parenthesis</dt>
			<dd> <code>
					<?php
					foreach ($parenthesis_stats as $p => $value) {
						foreach ($value as $key => $val) {
							echo " '" . substr($val, 1, strlen($val) - 2) . "' ";
						}
					}
					?>
				</code></dd>
			<dt>&nbsp;</dt>
			<dd><input type="submit" value="Check"></dd>
		</dl>

	</form>
</body>

</html>