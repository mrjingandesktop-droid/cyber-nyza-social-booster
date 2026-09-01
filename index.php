<?php
// ==================== BACKEND PHP (API EXECUTION) ====================
$message = "";
$status_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $provider_api_url = trim($_POST['api_url']);
    $api_key          = trim($_POST['api_key']);
    $service_id       = trim($_POST['service_id']);
    $target_link      = trim($_POST['target_link']);
    $quantity         = trim($_POST['quantity']);

    if (!empty($provider_api_url) && !empty($api_key) && !empty($service_id) && !empty($target_link) && !empty($quantity)) {
        
        // SMM Provider-ലേക്ക് അയക്കാനുള്ള ഡാറ്റ
        $api_data = array(
            'key'      => $api_key,
            'action'   => 'add',
            'service'  => $service_id,
            'link'     => $target_link,
            'quantity' => $quantity
        );

        // cURL റീക്വസ്റ്റ് അയക്കുന്നു
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $provider_api_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($api_data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        if (isset($result['order'])) {
            $status_type = "success";
            $message = "Order Success! Order ID: " . $result['order'];
        } elseif (isset($result['error'])) {
            $status_type = "error";
            $message = "Error: " . $result['error'];
        } else {
            $status_type = "error";
            $message = "API Connect ചെയ്യാൻ സാധിച്ചില്ല. Details പരിശോധിക്കുക.";
        }
    } else {
        $status_type = "error";
        $message = "എല്ലാ വിവരങ്ങളും നൽകുക!";
    }
}
?>

<!DOCTYPE html>
<html lang="ml">
<head>
    <!-- ==================== FRONTEND HTML & CSS ==================== -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMM 50k Followers Panel</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        body {
            background-color: #0d1117;
            color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .card {
            background: #161b22;
            padding: 25px;
            border-radius: 10px;
            border: 1px solid #30363d;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }
        h2 {
            text-align: center;
            color: #58a6ff;
            margin-bottom: 20px;
        }
        .field {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-size: 13px;
            color: #8b949e;
        }
        input {
            width: 100%;
            padding: 10px;
            background: #0d1117;
            border: 1px solid #30363d;
            border-radius: 5px;
            color: #fff;
            font-size: 14px;
        }
        input:focus {
            border-color: #58a6ff;
            outline: none;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #238636;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background: #2ea043;
        }
        .msg {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
            font-size: 14px;
        }
        .msg.success { background: #1b4721; color: #7ee787; }
        .msg.error { background: #4c1d1d; color: #ff7b72; }
    </style>
</head>
<body>

<div class="card">
    <h2>50k Followers Panel</h2>

    <?php if (!empty($message)): ?>
        <div class="msg <?php echo $status_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="" onsubmit="return validateForm()">
        <div class="field">
            <label>Provider API URL:</label>
            <input type="url" name="api_url" id="api_url" placeholder="https://cybersmm.site/api/v2" required>
        </div>

        <div class="field">
            <label>SMM Provider API Key:</label>
            <input type="text" name="api_key" id="api_key" placeholder="Enter Provider API Key" required>
        </div>

        <div class="field">
            <label>Service ID (Followers Service):</label>
            <input type="number" name="service_id" id="service_id" placeholder="eg: 1024" required>
        </div>

        <div class="field">
            <label>Instagram Link / Username:</label>
            <input type="text" name="target_link" id="target_link" placeholder="https://instagram.com/username" required>
        </div>

        <div class="field">
            <label>Quantity:</label>
            <input type="number" name="quantity" id="quantity" value="50000" required>
        </div>

        <button type="submit">Submit Order</button>
    </form>
</div>

<!-- ==================== JAVASCRIPT VALIDATION ==================== -->
<script>
function validateForm() {
    let link = document.getElementById("target_link").value;
    let qty = document.getElementById("quantity").value;

    if (qty < 10) {
        alert("കുറഞ്ഞത് 10 ഫോളോവേഴ്‌സ് എങ്കിലും നൽകണം!");
        return false;
    }
    return true;
}
</script>

</body>
</html>
