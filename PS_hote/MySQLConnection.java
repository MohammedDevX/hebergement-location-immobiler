import java.sql.*;

public class MySQLConnection {
    private static final String URL = "jdbc:mysql://localhost:3306/hebergement_particulier"; // Replace with your DB name
    private static final String USER = "root";
    private static final String PASSWORD = "db123";

    private Connection conn;

    public MySQLConnection() {
        try {
            // Establish the connection when the MySQLConnection object is created
            conn = DriverManager.getConnection(URL, USER, PASSWORD);
            System.out.println("✅ Connected to MySQL database.");
        } catch (SQLException e) {
            System.err.println("❌ Échec de la connexion à la base de données:");
            e.printStackTrace();
            System.exit(1);
        }
    }

    public Connection getConnection() {
        return conn;
    }

    public void closeConnection() {
        try {
            if (conn != null && !conn.isClosed()) {
                conn.close();
                System.out.println("🔌 Connection closed.");
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }
}
