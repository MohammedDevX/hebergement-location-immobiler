import java.awt.*;
import java.util.ArrayList;
import java.util.List;
import java.util.Scanner;
import javax.swing.*;
import javax.swing.filechooser.FileNameExtensionFilter;
import java.io.File;
import java.sql.*;

public class hote extends user {

    private int id;

    private List<annonce> annonces;

    public hote() {
        super();
        annonces = new ArrayList<>();
    }

    public hote(String username, String nom, String prenom, String email, String password, String phone) {
        super(username, nom, prenom, email, password, phone);
        annonces = new ArrayList<>();
    }

    public hote(int id, int idUser, String username, String nom, String prenom, String email, String password, String phone) {
        super(idUser,username, nom, prenom, email, password, phone);
        this.id = id;
        this.annonces = new ArrayList<>();
    }
    public hote(Connection conn,int id, String username, String nom, String prenom, String email, String password, String phone) {
        super(username, nom, prenom, email, password, phone);
        this.id = id;
        this.annonces = new ArrayList<>();
        Remplir_Annonce(conn);
    }

   /* public Boolean ajout_annonce() {
        Scanner scanner = new Scanner(System.in);

        System.out.println("=== Ajouter une Annonce ===");

        System.out.print("Titre: ");
        String titre = scanner.nextLine();

        System.out.print("Description: ");
        String description = scanner.nextLine();

        System.out.print("Adresse: ");
        String adress = scanner.nextLine();

        System.out.print("Ville: ");
        String ville = scanner.nextLine();

        System.out.print("Type: ");
        String type = scanner.nextLine();
        List<String> photos = new ArrayList<>();
        System.out.println("Sélectionnez les photos de l'annonce (appuyez sur 'OK' après avoir sélectionné les fichiers):");
        photos =PhotoSelector();
        annonce annonce_ = new annonce( titre, description, adress, ville, annonce.Type.valueOf(type), photos, this);
        annonces.add(annonce_);
        return true;
    }*/
    
    public void ajout_annonce(Connection conn) {
        Scanner scanner = new Scanner(System.in);

        System.out.println("=== Ajouter une Annonce ===");

        System.out.print("Titre: ");
        String titre = scanner.nextLine();

        System.out.print("Description: ");
        String description = scanner.nextLine();

        System.out.print("Adresse: ");
        String adress = scanner.nextLine();

        System.out.print("Ville: ");
        String ville = scanner.nextLine();
        
        System.out.print("Prix Par Nuit: ");
        Double prix = scanner.nextDouble();

        System.out.println("Please choose a type of accommodation:");
        System.out.println("1. Appartement");
        System.out.println("2. Maison");
        System.out.println("3. Studio");
        System.out.println("4. Chambre");

        int choice = 0;
        while (choice < 1 || choice > 4) {
            System.out.print("Enter your choice (1-4): ");
            if (scanner.hasNextInt()) {
                choice = scanner.nextInt();
            } else {
                scanner.next(); // clear invalid input
            }
        }

        annonce.Type type = annonce.Type.values()[choice - 1];


        List<String> photos = new ArrayList<>();
        System.out.println("Sélectionnez les photos de l'annonce :");
        photos =PhotoSelector();
        annonce annonce_ = new annonce( titre, description, adress, ville, type, photos, this,prix);
        annonces.add(annonce_);
        
        try {
            // Check if the city exists, and if not, insert it
            int idVille = -1;
            String villeQuery = "SELECT id_ville FROM ville WHERE nom_ville = ?";
            try (PreparedStatement psVille = conn.prepareStatement(villeQuery)) {
                psVille.setString(1, ville);
                ResultSet rsVille = psVille.executeQuery();
                if (rsVille.next()) {
                    idVille = rsVille.getInt("id_ville");
                } else {
                    // City doesn't exist, so we insert it
                    String insertVilleQuery = "INSERT INTO ville (nom_ville) VALUES (?)";
                    try (PreparedStatement psInsertVille = conn.prepareStatement(insertVilleQuery, Statement.RETURN_GENERATED_KEYS)) {
                        psInsertVille.setString(1, ville);
                        psInsertVille.executeUpdate();

                        ResultSet generatedKeys = psInsertVille.getGeneratedKeys();
                        if (generatedKeys.next()) {
                            idVille = generatedKeys.getInt(1); // Get the generated id_ville
                        }
                    }
                }
            }

            // Insert the annonce
            String insertQuery = "INSERT INTO annonce (titre, description_annonce, adresse, prix_nuit, capacite, type_logement, id_ville, id_hote) " +
                                 "VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            try (PreparedStatement psInsert = conn.prepareStatement(insertQuery)) {
                psInsert.setString(1, titre);
                psInsert.setString(2, description);
                psInsert.setString(3, adress);
                psInsert.setDouble(4, prix);
                psInsert.setInt(5, 4); // You can ask user for capacite instead of hardcoding it
                psInsert.setString(6, type.toString());
                psInsert.setInt(7, idVille);
                psInsert.setInt(8, this.id); // 'this' should be Hote with a valid 'id'

                int rowsInserted = psInsert.executeUpdate();
                if (rowsInserted > 0) {
                    System.out.println("Annonce ajoutée avec succès à la base de données.");
                }
            }
        } catch (SQLException e) {
            System.err.println("Erreur SQL lors de l'ajout de l'annonce : " + e.getMessage());
        }
        
        return ;
    }

    public List<String> PhotoSelector() {

            // Create a file chooser
            JFileChooser fileChooser = new JFileChooser();
            fileChooser.setMultiSelectionEnabled(true);

            // Set a filter for image files
            FileNameExtensionFilter filter = new FileNameExtensionFilter("Image Files", "jpg", "png", "jpeg", "gif");
            fileChooser.setFileFilter(filter);

            // Open the file chooser dialog
            int result = fileChooser.showOpenDialog(null);

            List<String> selectedFiles = new ArrayList<>();

            // Check if the user selected a file
            if (result == JFileChooser.APPROVE_OPTION) {
                System.out.println("TEST");
                File[] files = fileChooser.getSelectedFiles(); // Get selected files
                for (File file : files) {
                    selectedFiles.add(file.getAbsolutePath()); // Add each file to the list
                    System.out.println("Selected file: " + file.getAbsolutePath());
                }
            } else {
                System.out.println("No file selected.");
            }
            return selectedFiles;
    }

    public static void showPhotos(List<String> photoPaths) {
        if (photoPaths == null || photoPaths.isEmpty()) {
            System.out.println("No photos to display.");
            return;
        }

        JFrame frame = new JFrame("Photo Viewer");
        frame.setDefaultCloseOperation(JFrame.DISPOSE_ON_CLOSE);
        frame.setExtendedState(JFrame.MAXIMIZED_BOTH); // Maximize the window
        frame.setLayout(new BorderLayout());

        JLabel photoLabel = new JLabel("", JLabel.CENTER);
        frame.add(photoLabel, BorderLayout.CENTER);

        JPanel buttonPanel = new JPanel(new FlowLayout(FlowLayout.CENTER));
        JButton prevButton = new JButton("Previous");
        JButton nextButton = new JButton("Next");
        JButton closeButton = new JButton("Close");

        buttonPanel.add(prevButton);
        buttonPanel.add(nextButton);
        buttonPanel.add(closeButton);
        frame.add(buttonPanel, BorderLayout.SOUTH);

        final int[] currentIndex = {0};

        Runnable updatePhoto = () -> {
            File file = new File(photoPaths.get(currentIndex[0]));
            if (file.exists()) {
                ImageIcon imageIcon = new ImageIcon(photoPaths.get(currentIndex[0]));
                Image image = imageIcon.getImage();

                Dimension screenSize = Toolkit.getDefaultToolkit().getScreenSize();
                int screenWidth = screenSize.width;
                int screenHeight = screenSize.height - 100; // Adjust for taskbar and buttons

                int imgWidth = image.getWidth(null);
                int imgHeight = image.getHeight(null);

                double widthRatio = (double) screenWidth / imgWidth;
                double heightRatio = (double) screenHeight / imgHeight;
                double scale = Math.min(widthRatio, heightRatio);

                int newWidth = (int) (imgWidth * scale);
                int newHeight = (int) (imgHeight * scale);

                Image scaledImage = image.getScaledInstance(newWidth, newHeight, Image.SCALE_SMOOTH);
                photoLabel.setIcon(new ImageIcon(scaledImage));
            } else {
                photoLabel.setText("Photo not found: " + photoPaths.get(currentIndex[0]));
            }
        };

        prevButton.addActionListener(e -> {
            if (currentIndex[0] > 0) {
                currentIndex[0]--;
                updatePhoto.run();
            }
        });

        nextButton.addActionListener(e -> {
            if (currentIndex[0] < photoPaths.size() - 1) {
                currentIndex[0]++;
                updatePhoto.run();
            }
        });

        closeButton.addActionListener(e -> frame.dispose());

        updatePhoto.run();
        frame.setVisible(true);
    }

    @Override
    public String toString() {
        return "Hote (" + username +", " + nom+", "+ prenom +","+ email +", "+ password+")";
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }
    public void Remplir_Annonce(Connection conn) {
        String query = "SELECT an.id_annonce, an.titre, an.description_annonce, an.prix_nuit, an.adresse, an.type_logement, v.nom_ville " +
                "FROM hote h " +
                "JOIN annonce an ON h.id_hote = an.id_hote " +
                "JOIN ville v ON an.id_ville = v.id_ville " +
                "WHERE an.id_hote = ?";  // Use parameterized query to avoid SQL injection

        try (PreparedStatement ps = conn.prepareStatement(query)) {
            // Set the ID_Hote parameter in the query
            ps.setInt(1, this.id);

            try (ResultSet rs = ps.executeQuery()) {
                while (rs.next()) {
                    int idAnnonce = rs.getInt("id_annonce");
                    String titre = rs.getString("titre");
                    String description = rs.getString("description_annonce");
                    double prix = rs.getDouble("prix_nuit");
                    String adresse = rs.getString("adresse");
                    String typeStr = rs.getString("type_logement");
                    annonce.Type type = annonce.Type.valueOf(typeStr);
                    String ville = rs.getString("nom_ville");

                    // Create Annonce object from query results
                    annonce annonce = new annonce(conn, idAnnonce, titre, description, adresse, prix, type, ville);

                    // Add the Annonce to the list
                    this.annonces.add(annonce);
                }
            }
        } catch (SQLException e) {
            System.err.println("Erreur lors du chargement des annonces : " + e.getMessage());
        }
    }
    
    public void afficherLesAnnonces() {
        for (int i = 0; i < this.annonces.size(); i++) {
            this.annonces.get(i).afficherAnnonce();
        }
    }
    
    public String getFullName() {
    	String N=this.nom+" "+this.prenom;;
    	return N;
    }
    public void displayHoteProfile() {
    	System.out.println("Nom: \t\t" + this.nom);
        System.out.println("Prenom: \t" + this.prenom);
        //System.out.println("Username: \t" + this.username);
        System.out.println("Email: \t\t" + this.email);
        System.out.println("Tel: \t\t" + this.num_tele);

    }
    
    public void modifierNom(Connection conn,Scanner scanner,int idU) {
    	System.out.println("Nom current: \t\t" + this.nom);
    	System.out.print("Nouveau Nom: \t\t");
    	String N= scanner.nextLine();
    	if (N.isEmpty()) {
            System.out.println("Modification annulée.");
            return;
        }

        String query = "UPDATE locataire SET nom = ? WHERE id_locataire = ?";

        try (PreparedStatement stmt = conn.prepareStatement(query)) {
            stmt.setString(1, N);   
            stmt.setInt(2, idU);    

            int rows = stmt.executeUpdate();
            if (rows > 0) {
                this.nom = N; 
                System.out.println("Nom modifié avec succès dans la base de données.");
            } else {
                System.out.println("Aucune mise à jour effectuée. Vérifiez l'ID.");
            }
        } catch (SQLException e) {
            System.err.println("Erreur lors de la mise à jour du nom: " + e.getMessage());
        }
    }
    public int getid() {return this.id;}
    public void modifierPrenom(Connection conn,Scanner scanner,int idU) {
    	System.out.println("Prenom current: \t\t" + this.prenom);
    	System.out.print("Nouveau Prenom: \t\t");
    	String N= scanner.nextLine();
    	if (N.isEmpty()) {
            System.out.println("Modification annulée.");
            return;
        }

        String query = "UPDATE locataire SET prenom = ? WHERE id_locataire = ?";

        try (PreparedStatement stmt = conn.prepareStatement(query)) {
            stmt.setString(1, N);   
            stmt.setInt(2, idU);    

            int rows = stmt.executeUpdate();
            if (rows > 0) {
                this.prenom = N; 
                System.out.println("Prenom modifié avec succès dans la base de données.");
            } else {
                System.out.println("Aucune mise à jour effectuée. Vérifiez l'ID.");
            }
        } catch (SQLException e) {
            System.err.println("Erreur lors de la mise à jour du prenom: " + e.getMessage());
        }
    }
    
    public void modifierEmail(Connection conn,Scanner scanner,int idU) {
    	System.out.println("Email current: \t\t" + this.email);
    	System.out.print("Nouveau Email: \t\t");
    	String N= scanner.nextLine();
    	if (N.isEmpty()) {
            System.out.println("Modification annulée.");
            return;
        }

        String query = "UPDATE locataire SET email = ? WHERE id_locataire = ?";

        try (PreparedStatement stmt = conn.prepareStatement(query)) {
            stmt.setString(1, N);   
            stmt.setInt(2, idU);    

            int rows = stmt.executeUpdate();
            if (rows > 0) {
                this.email = N; 
                System.out.println("email modifié avec succès dans la base de données.");
            } else {
                System.out.println("Aucune mise à jour effectuée. Vérifiez l'ID.");
            }
        } catch (SQLException e) {
            System.err.println("Erreur lors de la mise à jour du email: " + e.getMessage());
        }
    }
    
    public void modifierTel(Connection conn,Scanner scanner,int idU) {
    	System.out.println("Numero du telephone current: \t\t" + this.num_tele);
    	System.out.print("Nouveau Numero du telephone: \t\t");
    	String N= scanner.nextLine();
    	if (N.isEmpty()) {
            System.out.println("Modification annulée.");
            return;
        }

        String query = "UPDATE locataire SET tel = ? WHERE id_locataire = ?";

        try (PreparedStatement stmt = conn.prepareStatement(query)) {
            stmt.setString(1, N);   
            stmt.setInt(2, idU);    

            int rows = stmt.executeUpdate();
            if (rows > 0) {
                this.num_tele = N; 
                System.out.println("Numero du telephone modifié avec succès dans la base de données.");
            } else {
                System.out.println("Aucune mise à jour effectuée. Vérifiez l'ID.");
            }
        } catch (SQLException e) {
            System.err.println("Erreur lors de la mise à jour du Numero du telephone: " + e.getMessage());
        }
    }
    
    public void modifierMdp(Connection conn,Scanner scanner,int idU) {
    	System.out.print("Nouveau MDP: \t\t");
    	String N1= scanner.nextLine();
    	System.out.print("Resaisie Nouveau MDP: \t\t");
    	String N= scanner.nextLine();
    	if (N.isEmpty()) {
            System.out.println("Modification annulée.");
            return;
        }
    	if (!N1.equals(N)) {
            System.out.println("❌ Les mots de passe ne correspondent pas. Modification annulée.");
            return;
        }

        String query = "UPDATE locataire SET mot_passe = ? WHERE id_locataire = ?";

        try (PreparedStatement stmt = conn.prepareStatement(query)) {
            stmt.setString(1, N);   
            stmt.setInt(2, idU);    

            int rows = stmt.executeUpdate();
            if (rows > 0) {
                this.password = N; 
                System.out.println("MDP modifié avec succès dans la base de données.");
            } else {
                System.out.println("Aucune mise à jour effectuée. Vérifiez l'ID.");
            }
        } catch (SQLException e) {
            System.err.println("Erreur lors de la mise à jour du MDP: " + e.getMessage());
        }
    }
    
    public annonce getannonce(int i) {
    	return annonces.get(i);
    }
    public List<annonce> getAnnonces() {
        return annonces;
    }
}
