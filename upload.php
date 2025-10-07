<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Fitness Analysis</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
require __DIR__ . '/config.php';
require 'vendor/autoload.php';
use OpenAI as OpenAIAPI;

$apiKey = $OPENAI_API_KEY;
$client = OpenAIAPI::client($apiKey);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = htmlspecialchars($_POST["name"]);
    $surname = htmlspecialchars($_POST["surname"]);
    $email = htmlspecialchars($_POST["email"]);
    $year = htmlspecialchars($_POST["year"]);
    $height = htmlspecialchars($_POST["height"]);
    $weight = htmlspecialchars($_POST["weight"]);

    $prompt = "User: $name $surname ($email)\n".
              "Age: $year years\n".
              "Height: $height cm, Weight: $weight kg.\n".
              "Based on this data, provide a professional analysis of the user's physical condition and detailed recommendations about:\n".
              "- Nutrition (calories, meals, food types)\n".
              "- Daily water intake\n".
              "- Physical activity and exercises.\n".
              "Answer clearly, professionally, and include numerical values where possible.";

    try {
        $response = $client->responses()->create([
            'model' => 'gpt-4o-mini', 
            'input' => [
                ['role' => 'user', 'content' => $prompt]
            ],
        ]);

        if (isset($response->output[0]->content[0]->text)) {
            $aiText = $response->output[0]->content[0]->text;
            echo "<div class='ai-response'>";
            echo "<h3>AI Fitness Analysis for $name $surname</h3>";
            echo "<pre>$aiText</pre>";
            echo "</div>";
        } else {
            echo "<p> The model did not return a valid text response.</p>";
        }

    } catch (Exception $e) {
        echo "<div class='ai-response'>";
        echo "<h2>ERROR:</h2><pre>" . $e->getMessage() . "</pre>";
        echo "</div>";
    }
}
?> 

</body>
</html>
