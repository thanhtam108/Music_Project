<?php

require_once 'DBConnection.php';

class Order
{
    var $order_id;
    var $customer_phone;
    var $order_datetime;
    var $order_total_amount;
    var $order_notes;

    protected $conn;

    public function __construct()
    {
        $this->order_id = "";
        $this->customer_phone = "";
        $this->order_datetime = "";
        $this->order_total_amount = 0;
        $this->order_notes = "";
        $connectObj = new DBConnection();
        $this->conn = $connectObj->conn;
    }

    function get_order($customer_phone, $datetime)
    {
        try {
            $sql = "SELECT * FROM T_ORDER WHERE CUSTOMER_PHONE = ? AND ORDER_DATETIME = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ss', $customer_phone, $datetime);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (Exception $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    function get_ord_devices($order_id)
    {
        try {
            $sql = "SELECT * FROM T_ORDER-DEVICES WHERE ORDER_ID = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('s', $order_id);
            $stmt->execute();
            $result_array = array();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $result_array[] = $row;
            }
            return $result_array;
        } catch (Exception $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    function get_pending_order()
    {
        try {
            $sql = "SELECT * FROM T_TEMPORARY_ORDER";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result_array = array();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $result_array[] = $row;
            }
            return $result_array;
        } catch (Exception $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    function get_pending_devices($order_id)
    {
        try {
            $sql = "SELECT d.*, t.* FROM T_TEMPORARY_ORDER_DEVICES t
            JOIN T_DEVICE d ON t.DEV_ID = d.DEV_ID
            WHERE t.TEMP_ORDER_ID = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $order_id);
            $stmt->execute();

            $result_array = array();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $result_array[] = $row;
            }
            return $result_array;
        } catch (Exception $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    public function get_customer_info($customer_phone)
    {
        $stmt = $this->conn->prepare("SELECT CUSTOMER_NAME, CUSTOMER_EMAIL, CUSTOMER_ADDRESS FROM T_CUSTOMER WHERE CUSTOMER_PHONE = ?");
        $stmt->bind_param("s", $customer_phone);
        $stmt->execute();
        $result = $stmt->get_result();
        $customer_info = $result->fetch_assoc();
        return $customer_info;
    }


    function confirm_order($temp_order_id)
    {
        try {
            // Bắt đầu giao dịch
            $this->conn->begin_transaction();

            // Truy xuất đơn hàng tạm thời
            $ord_sql = "SELECT * FROM T_TEMPORARY_ORDER WHERE TEMP_ORDER_ID = ?";
            $stmt_ord = $this->conn->prepare($ord_sql);
            $stmt_ord->bind_param("i", $temp_order_id);
            $stmt_ord->execute();
            $order_result = $stmt_ord->get_result()->fetch_assoc();

            // Truy xuất các thiết bị của đơn hàng tạm thời
            $dev_sql = "SELECT * FROM T_TEMPORARY_ORDER_DEVICES WHERE TEMP_ORDER_ID = ?";
            $stmt_dev = $this->conn->prepare($dev_sql);
            $stmt_dev->bind_param("i", $temp_order_id);
            $stmt_dev->execute();
            $dev_result = $stmt_dev->get_result()->fetch_all(MYSQLI_ASSOC);

            // Chèn đơn hàng vào bảng đơn hàng chính
            $insert_order_sql = "INSERT INTO T_ORDER (CUSTOMER_PHONE, ORDER_DATETIME, ORDER_TOTAL_AMOUNT, ORDER_NOTES) VALUES (?,?,?,?)";
            $stmt_insert_order = $this->conn->prepare($insert_order_sql);
            $stmt_insert_order->bind_param(
                "ssis",
                $order_result['CUSTOMER_PHONE'],
                $order_result['Order_DateTime'],
                $order_result['Total_amount'],
                $order_result['ORDER_NOTES']
            );
            $stmt_insert_order->execute();

            // Truy xuất đơn hàng vừa chèn
            $new_order = $this->get_order($order_result['CUSTOMER_PHONE'], $order_result['Order_DateTime']);
            if (!$new_order) {
                throw new Exception("Failed to retrieve new order.");
            }
            $order_id = $new_order['ORDER_ID'];

            // Chèn các thiết bị của đơn hàng vào bảng thiết bị đơn hàng chính
            $insert_dev_sql = "INSERT INTO T_ORDER_DEVICES (ORDER_ID, DEV_ID) VALUES (?, ?)";
            $stmt_insert_dev = $this->conn->prepare($insert_dev_sql);
            if (!$stmt_insert_dev) {
                throw new Exception("Prepare failed: " . $this->conn->error);
            }

            foreach ($dev_result as $device) {
                $stmt_insert_dev->bind_param("ii", $order_id, $device['DEV_ID']);
                if (!$stmt_insert_dev->execute()) {
                    throw new Exception("Execution failed: " . $stmt_insert_dev->error);
                }
            }

            // Xóa đơn hàng tạm thời và các thiết bị liên quan sau khi chèn thành công
            $delete_temp_dev_sql = "DELETE FROM T_TEMPORARY_ORDER_DEVICES WHERE TEMP_ORDER_ID = ?";
            $stmt_delete_temp_dev = $this->conn->prepare($delete_temp_dev_sql);
            $stmt_delete_temp_dev->bind_param("i", $temp_order_id);
            if (!$stmt_delete_temp_dev->execute()) {
                throw new Exception("Failed to delete temporary order devices: " . $stmt_delete_temp_dev->error);
            }
            
            $delete_temp_order_sql = "DELETE FROM T_TEMPORARY_ORDER WHERE TEMP_ORDER_ID = ?";
            $stmt_delete_temp_order = $this->conn->prepare($delete_temp_order_sql);
            $stmt_delete_temp_order->bind_param("i", $temp_order_id);
            if (!$stmt_delete_temp_order->execute()) {
                throw new Exception("Failed to delete temporary order: " . $stmt_delete_temp_order->error);
            }


            // Cam kết giao dịch
            $this->conn->commit();

            echo "ORDER IS APPROVED";
        } catch (Exception $e) {
            // Rollback nếu có lỗi xảy ra
            $this->conn->rollback();
            die("Query failed: " . $e->getMessage());
        }
    }
}
