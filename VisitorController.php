<?php

require 'vendor/autoload.php';

include_once "db.php";

// use  PHPMailer\PHPMailer\PHPMailer;
// use  PHPMailer\PHPMailer\Exception;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\ValidationException;


class VisitorController{
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function ApplyVisitor($name, $IC, $email, $phone, $visitor_code, $visit_date, $status, $owner_id, $valid_days){
        // 设置二维码过期日期
        $expiration_time = strtotime($visit_date . ' +' . $valid_days . ' days');

        // QR Code 数据信息
        $qr_data = json_encode([
           "visitor_code" => $visitor_code
        ]);
        //{"visitor_code" : "SHAOXI_27092024"}
        // 生成 QR Code
        // $result = Builder::create()
        // ->writer(new PngWriter())
        // ->data($qr_data)
        // ->encoding(new Encoding('UTF-8'))
        // ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
        // ->size(300)
        // ->margin(10)
        // ->build();

        $qrCode = new QrCode(
            data: $qr_data,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Low,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255)
        );

        $writer = new PngWriter();
        $filePath = '../qrcodes/' . $visitor_code . '.png';
        
        // Write the QR code to the file
        $result = $writer->write($qrCode);
        $result->saveToFile($filePath);

        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM visitors WHERE visitor_code = ?");
        $stmt->bind_param("s", $visitor_code);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            echo "Error: Visitor code already exists.";
            return;
        }else{

            // 插入访客数据
            $stmt = $this->conn->prepare("INSERT INTO visitors (name, IC, email, phone, visitor_code, qr_code, visit_date, status, owner_id, valid_days) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?)");
            $stmt->bind_param("ssssssssss", $name, $IC, $email, $phone, $visitor_code, $filePath, $visit_date, $status, $owner_id, $valid_days);
            $stmt->execute();

            // 获取插入的访客 ID
            $visitor_id = $stmt->insert_id;
            $stmt->close();

            // 插入 QR Code 数据到 qr_codes 表
            $stmt = $this->conn->prepare("INSERT INTO qr_codes (visitor_id, qr_code, generated_at, expires_at) VALUES (?, ?, ?, ?)");
            $generated_at = date('Y-m-d H:i:s');
            $expires_at = date('Y-m-d H:i:s', $expiration_time);
            $stmt->bind_param("isss", $visitor_id, $visitor_code, $generated_at, $expires_at);
            $stmt->execute();
            $stmt->close();

            // 发送电子邮件包含 QR Code
            // $this->sendEmailWithQRCode($email, $name, $filePath);
        }
    }


    private function sendEmailWithQRCode($email,$name,$filePath){
        $mail = new PHPMailer(true);

        try {
            // 邮件服务器配置
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';  // 设置 SMTP 服务器地址
            $mail->SMTPAuth   = true;
            $mail->Username   = 'shaoxiclass@gmail.com';  // SMTP 用户名
            $mail->Password   = 'azqgmxnnkdbzjvna';  // SMTP 密码
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // 收件人配置
            $mail->setFrom('shaoxiclass@gmail.com', 'ABC Propetry');
            $mail->addAddress($email, $name);

            // 附件
            $mail->addAttachment($filePath);  // 添加二维码图片作为附件

            // 内容配置
            $mail->isHTML(true);
            $mail->Subject = 'Your Visit QR Code';
            $mail->Body    = 'Dear ' . $name . ',<br><br>Please find attached your QR code for the upcoming visit.';

            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    // public function VerifyQRCode($scannedData) {
    //     // 解析扫描到的数据
    //     $data = json_decode($scannedData, true);
        
    //     // 确保数据解析成功
    //     if (!isset($data['visitor_code']) || !isset($data['expiration_time'])) {
    //         return "Invalid QR Code Data";
    //     }
        
    //     $visitor_code = $data['visitor_code'];
    //     $expiration_time = $data['expiration_time'];
        
    //     // 当前时间
    //     $current_time = time();
        
    //     // 检查二维码是否过期
    //     if ($current_time > $expiration_time) {
    //         return "QR Code Expired";
    //     }
        
    //     // 查询访客信息和 QR 码信息
    //     $stmt = $this->conn->prepare("
    //         SELECT v.id, v.name, v.IC, v.email, v.phone, q.generated_at, q.expires_at
    //         FROM visitors AS v
    //         INNER JOIN qr_codes AS q ON v.id = q.visitor_id
    //         WHERE q.qr_code = ? AND q.expires_at > ?
    //     ");
        
    //     // 绑定参数并执行查询
    //     $stmt->bind_param("si", $visitor_code, $current_time);
    //     $stmt->execute();
    //     $result = $stmt->get_result();

    //     if ($result->num_rows > 0) {
    //         // 访客记录和二维码匹配
    //         $visitor = $result->fetch_assoc();

    //         // 检查访问日期是否已到
    //         if ($current_time < strtotime($visitor['generated_at'])) {
    //             return "Visit date not reached yet!";
    //         }

    //         // 返回访客信息，表示 QR Code 有效
    //         return $visitor;
    //     } else {
    //         // QR Code 无效
    //         return "QR Code Invalid";
    //     }
    // }


    public function getVisitors($owner_id) {
        // 准备查询语句
        $stmt = $this->conn->prepare("SELECT * FROM visitors WHERE owner_id = ?");
        if (!$stmt) {
            // 如果预处理失败，输出错误信息并返回 false
            echo "SQL prepare failed: (" . $this->conn->errno . ") " . $this->conn->error;
            return false;
        }

        // 绑定参数
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();

        // 获取查询结果
        $result = $stmt->get_result();

        if (!$result) {
            // 如果获取结果失败，输出错误信息并返回 false
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        }

        // 返回查询结果
        return $result;
    }

    public function viewQRCode($visitor_id){
        $stmt = $this->conn->prepare("SELECT * FROM visitors WHERE id = ?");
        if (!$stmt) {
            echo "SQL prepare failed: (" . $this->conn->errno . ") " . $this->conn->error;
            return false;
        }

        $stmt->bind_param("i", $visitor_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if (!$result) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        }

        return $result;
    }

    public function getVisitorsID($visitor_id){
         // 准备查询语句
         $stmt = $this->conn->prepare("SELECT * FROM visitors WHERE id = ?");
         if (!$stmt) {
             // 如果预处理失败，输出错误信息并返回 false
             echo "SQL prepare failed: (" . $this->conn->errno . ") " . $this->conn->error;
             return false;
         }
 
         // 绑定参数
         $stmt->bind_param("i", $owner_id);
         $stmt->execute();
 
         // 获取查询结果
         $result = $stmt->get_result();
 
         if (!$result) {
             // 如果获取结果失败，输出错误信息并返回 false
             echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
             return false;
         }
 
         // 返回查询结果
         return $result;
    }

    public function UpdateVisitor($name, $IC, $email, $phone, $visitor_code, $visit_date, $status, $visitor_id, $valid_days) {
        try {
            // Set QR Code expiration date
            $expiration_time = strtotime($visit_date . ' +' . $valid_days . ' days');

            // QR Code data
            $qr_data = json_encode([
                "visitor_code" => $visitor_code
            ]);

            // Generate QR Code using Endroid library
            $qrCode = new QrCode(
                data: $qr_data,
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::Low,
                size: 300,
                margin: 10,
                roundBlockSizeMode: RoundBlockSizeMode::Margin,
                foregroundColor: new Color(0, 0, 0),
                backgroundColor: new Color(255, 255, 255)
            );

            $writer = new PngWriter();
            $filePath = '../qrcodes/' . $visitor_code . '.png';
            
            // Write the QR code to the file
            $result = $writer->write($qrCode);
            $result->saveToFile($filePath);

            // Update visitor information
            $stmt = $this->conn->prepare('UPDATE visitors SET 
                name = ?, 
                IC = ?, 
                email = ?, 
                phone = ?, 
                visitor_code = ?, 
                qr_code = ?, 
                visit_date = ?, 
                status = ?, 
                valid_days = ? 
                WHERE id = ?');

            if (!$stmt) {
                error_log("Prepare failed: " . $this->conn->error);
                return false;
            }

            $stmt->bind_param('sssssssssi', 
                $name, 
                $IC, 
                $email, 
                $phone, 
                $visitor_code, 
                $filePath, 
                $visit_date,  // Make sure this is in the correct format (YYYY-MM-DD)
                $status, 
                $valid_days, 
                $visitor_id
            );

            $success = $stmt->execute();
            
            if (!$success) {
                error_log("Update failed: " . $stmt->error);
            }
            
            $stmt->close();
            return $success;

        } catch (Exception $e) {
            error_log("Error updating visitor: " . $e->getMessage());
            return false;
        }
    }

}




?>