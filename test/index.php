<!DOCTYPE html>
<html>
<head>
  <style>
    /* CSS样式 */
    .input-container {
      position: relative;
      width: 300px;
      margin: 20px;
    }
    
    .input-container input {
      width: 100%;
      padding: 10px;
      border: 2px solid #FFF;
      background-color: rgba(255, 255, 255, 0.5);
      border-radius: 5px;
      font-size: 16px;
      color: #333;
      transition: background-color 0.3s ease-in-out, transform 0.3s ease-in-out, border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }
    
    .input-container input:focus {
      background-color: rgba(255, 255, 255, 0.7);
      transform: scale(1.1);
      border-color: #AAA;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }
    
    .input-container::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      border: 2px solid #FFF;
      border-radius: 5px;
    }
    
    .input-container input::placeholder {
      color: #000;
    }
  </style>
</head>
<body>
  <div class="input-container">
    <input type="text" placeholder="请输入文本">
  </div>
</body>
</html>
