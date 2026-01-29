<?php
// database/Database.php

class Database
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = new PDO("sqlite:" . __DIR__ . "/../database/database.db");
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->initializeTables();
    }

    private function initializeTables(): void
    {
        // Tabela de clientes
        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS customers (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                email TEXT UNIQUE NOT NULL,
                name TEXT NOT NULL,
                phone TEXT,
                cpf TEXT,
                address TEXT,
                city TEXT,
                state TEXT,
                zip_code TEXT,
                country TEXT DEFAULT 'BR',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");

        // Tabela de pedidos
        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS orders (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                customer_id INTEGER,
                customer_email TEXT NOT NULL,
                customer_name TEXT NOT NULL,
                customer_phone TEXT,
                customer_cpf TEXT,
                address TEXT NOT NULL,
                city TEXT NOT NULL,
                state TEXT NOT NULL,
                zip_code TEXT NOT NULL,
                country TEXT DEFAULT 'BR',
                shipping_type TEXT DEFAULT 'sedex',
                shipping_cost REAL DEFAULT 0.0,
                order_items TEXT NOT NULL,
                total_amount REAL NOT NULL,
                mercado_pago_id TEXT,
                status TEXT DEFAULT 'pending',
                observations TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL
            )
        ");
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * Salva ou atualiza um cliente
     */
    public function saveCustomer(array $data): int
    {
        // Verifica se cliente já existe pelo email
        $stmt = $this->connection->prepare("SELECT id FROM customers WHERE email = :email");
        $stmt->execute([':email' => $data['email']]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            // Atualiza cliente existente
            $stmt = $this->connection->prepare("
                UPDATE customers SET 
                    name = :name,
                    phone = :phone,
                    cpf = :cpf,
                    address = :address,
                    city = :city,
                    state = :state,
                    zip_code = :zip_code,
                    updated_at = CURRENT_TIMESTAMP
                WHERE email = :email
            ");

            $stmt->execute([
                ':name' => $data['name'],
                ':phone' => $data['phone'],
                ':cpf' => $data['cpf'],
                ':address' => $data['address'],
                ':city' => $data['city'],
                ':state' => $data['state'],
                ':zip_code' => $data['zip_code'],
                ':email' => $data['email']
            ]);

            return $existing['id'];
        } else {
            // Cria novo cliente
            $stmt = $this->connection->prepare("
                INSERT INTO customers (
                    email, name, phone, cpf, address, city, state, zip_code, country
                ) VALUES (
                    :email, :name, :phone, :cpf, :address, :city, :state, :zip_code, :country
                )
            ");

            $stmt->execute([
                ':email' => $data['email'],
                ':name' => $data['name'],
                ':phone' => $data['phone'],
                ':cpf' => $data['cpf'],
                ':address' => $data['address'],
                ':city' => $data['city'],
                ':state' => $data['state'],
                ':zip_code' => $data['zip_code'],
                ':country' => $data['country'] ?? 'BR'
            ]);

            return (int)$this->connection->lastInsertId();
        }
    }

    /**
     * Busca cliente por email
     */
    public function getCustomerByEmail(string $email): ?array
    {
        $stmt = $this->connection->prepare("SELECT * FROM customers WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Cria um pedido
     */
    public function createOrder(array $data): int
    {
        $stmt = $this->connection->prepare("
            INSERT INTO orders (
                customer_id, customer_email, customer_name, customer_phone, customer_cpf,
                address, city, state, zip_code, country,
                shipping_type, shipping_cost, order_items, total_amount, observations
            ) VALUES (
                :customer_id, :customer_email, :customer_name, :customer_phone, :customer_cpf,
                :address, :city, :state, :zip_code, :country,
                :shipping_type, :shipping_cost, :order_items, :total_amount, :observations
            )
        ");

        $stmt->execute([
            ':customer_id' => $data['customer_id'] ?? null,
            ':customer_email' => $data['customer_email'],
            ':customer_name' => $data['customer_name'],
            ':customer_phone' => $data['customer_phone'],
            ':customer_cpf' => $data['customer_cpf'],
            ':address' => $data['address'],
            ':city' => $data['city'],
            ':state' => $data['state'],
            ':zip_code' => $data['zip_code'],
            ':country' => $data['country'] ?? 'BR',
            ':shipping_type' => $data['shipping_type'] ?? 'sedex',
            ':shipping_cost' => $data['shipping_cost'] ?? 0.0,
            ':order_items' => json_encode($data['order_items'], JSON_UNESCAPED_UNICODE),
            ':total_amount' => $data['total_amount'],
            ':observations' => $data['observations'] ?? null
        ]);

        return (int)$this->connection->lastInsertId();
    }

    /**
     * Retorna um pedido pelo ID (FALTAVA ESTE MÉTODO!)
     */
    public function getOrder(int $id): ?array
    {
        $stmt = $this->connection->prepare("SELECT * FROM orders WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order && isset($order['order_items'])) {
            $order['order_items'] = json_decode($order['order_items'], true);
        }

        return $order ?: null;
    }

    /**
     * Atualiza pedido com ID do Mercado Pago
     */
    public function updateOrderWithMercadoPagoId(int $orderId, string $mpId): bool
    {
        $stmt = $this->connection->prepare("
            UPDATE orders SET mercado_pago_id = :mp_id WHERE id = :order_id
        ");

        return $stmt->execute([
            ':mp_id' => $mpId,
            ':order_id' => $orderId
        ]);
    }

    /**
     * Atualiza status do pedido
     */
    public function updateOrderStatus(int $orderId, string $status): bool
    {
        $stmt = $this->connection->prepare("
            UPDATE orders SET status = :status WHERE id = :order_id
        ");

        return $stmt->execute([
            ':status' => $status,
            ':order_id' => $orderId
        ]);
    }

    /**
     * Busca pedidos por email do cliente
     */
    public function getOrdersByEmail(string $email): array
    {
        $stmt = $this->connection->prepare("
            SELECT * FROM orders 
            WHERE customer_email = :email 
            ORDER BY created_at DESC
        ");
        $stmt->execute([':email' => $email]);

        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($orders as &$order) {
            if (isset($order['order_items'])) {
                $order['order_items'] = json_decode($order['order_items'], true);
            }
        }

        return $orders;
    }

    /**
     * Busca todos os pedidos (para admin)
     */
    public function getAllOrders(): array
    {
        $stmt = $this->connection->prepare("
            SELECT * FROM orders 
            ORDER BY created_at DESC
        ");
        $stmt->execute();

        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($orders as &$order) {
            if (isset($order['order_items'])) {
                $order['order_items'] = json_decode($order['order_items'], true);
            }
        }

        return $orders;
    }
}