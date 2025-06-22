import java.util.ArrayList;
import java.util.Date;
import java.util.List;
//import java.io.Console;
import java.util.Scanner;
import java.sql.*;
import javax.swing.*;
import javax.swing.filechooser.FileNameExtensionFilter;
import java.io.File;

public class mainnn {
    public static void main(String[] args) {

    	MySQLConnection A = new MySQLConnection();
    	hote B=new hote();
    	//annonce B=new annonce();
    	//B.ajouterDisponibilite(A.getConnection());
    	//hote B=simpleAuth(A.getConnection());
    	//showHoteMenu(A.getConnection(),B);
    	B.ajout_annonce(A.getConnection());
    	//B.afficherLesAnnonces();
//       annonce ann = new annonce();
//        List<Date> newD = new ArrayList<>();
//            newD.add(new Date(2025 - 1900, 4, 20));
//            newD.add(new Date(2025 - 1900, 4, 7));
//            newD.add(new Date(2025 - 1900, 4, 1));
//            newD.add(new Date(2025 - 1900, 4, 16));
//            newD.add(new Date(2025 - 1900, 4, 5));
//            newD.add(new Date(2025 - 1900, 4, 9));
//            ann.ajout_Disponibilite(newD);
//            ann.afficherDisponibilite();
//        System.out.println("-----------------");
//            ann.updateDisponibilite();
//            ann.afficherDisponibilite();


       // hote Hote = new hote();
       // hote.showPhotos(Hote.PhotoSelector());
    	return;
    	
    }
    
    
    public static hote simpleAuth(Connection conn) {
    	//Console console = System.console();
        Scanner scanner = new Scanner(System.in);
        String email, password;

        while (true) {
        	
            System.out.println("\n=== Login ===");
            System.out.print("Email: ");
            email = scanner.nextLine();
            System.out.print("Password: ");
            password = scanner.nextLine();
            //char[] passwordChars = console.readPassword("Enter your password: ");
            //password = new String(passwordChars); // You can replace this with scanner.nextLine()

            String query = "SELECT h.Id_hote,l.nom,l.prenom,l.email,l.mot_passe,l.tel"
            		+ " FROM hote h Join locataire l on l.id_locataire=h.id_locataire"
            		+ " WHERE email = ? AND mot_passe = ? LIMIT 1";

            try (PreparedStatement ps = conn.prepareStatement(query)) {
                ps.setString(1, email);
                ps.setString(2, password);

                try (ResultSet rs = ps.executeQuery()) {
                    if (rs.next()) {
                        System.out.println("Login successful!\n");

                        return new hote(conn,
                            rs.getInt("Id_hote"),
                            "username",
                            rs.getString("nom"),
                            rs.getString("prenom"),
                            rs.getString("email"),
                            rs.getString("mot_passe"),
                            rs.getString("tel")
                        );
                    } else {
                        System.out.println("Adresse email ou mot de passe incorrect. Veuillez réessayer.");
                    }
                }
            } catch (SQLException e) {
                System.err.println("Login error: " + e.getMessage());
            }
        }
    }
    
    public static void showHoteMenu(Connection conn, hote Hote) {
        Scanner scanner = new Scanner(System.in);
        int choice;

        while (true) {
            System.out.println("\n===== Hote MENU =====");
            System.out.println("1. Your Profile");
            System.out.println("2. Modifier Profile");
            System.out.println("3. Consulter Anonnces");
            System.out.println("4. Ajouter Annonce");
            System.out.println("5. Ajouter Indisponibilite");
            System.out.println("6. Supprimer Indisponibilite");
            System.out.println("0. Logout");
            System.out.print("Enter your choice: ");

            if (!scanner.hasNextInt()) {
                scanner.nextLine(); // clear the invalid input
                System.out.println("Invalid option. Please enter a number between 0 and 6.");
                continue;
            }
            choice = scanner.nextInt();

            switch (choice) {
                case 1:
                    System.out.println("\n--- Your Profile ---");
                    Hote.displayHoteProfile();
                    System.out.println("\n--------------------");
                    break;

                case 2:
                    System.out.println("\n--- Modification du Profile ---");
                    menuModification(conn,Hote);
                    System.out.println("\n--------------------");
                    break;
  
                case 3:
                    System.out.println("\n--- Consulter Vos Annonces ---");
                    Hote.afficherLesAnnonces();
                    System.out.println("\n--------------------");
                    break;
                
                case 4:
                    System.out.println("\n--- Ajouter Une Annonces ---");
                    Hote.ajout_annonce(conn);
                    System.out.println("\n--------------------");
                    break;
                    
                case 5:
                	int i=0,indice;
                    System.out.println("\n--- Ajouter Une/Des Date(s) Indisponible(s) ---");
                    System.out.print("Saise la position de l'annonce que vous voullez ajouter une date indisponible: ");
                    do {
                    	if(i>0)System.out.print("la position que vous avez saise est en hors de vos annonces, Veuillez ressaiser: ");
                    indice=scanner.nextInt();
                    i++;
                    }while(indice>Hote.getAnnonces().size());
                    Hote.getannonce(i-1).ajouterDisponibilite(conn);
                    System.out.println("\n--------------------");
                    break;
                    
                case 6:
                	i=0;
                	indice=0;
                    System.out.println("\n--- Supprimer Une/Des Date(s) Indisponible(s) ---");
                    System.out.print("Saise la position de l'annonce que vous voullez supprimer une date indisponible: ");
                    do {
                    	if(i>0)System.out.print("la position que vous avez saise est en hors de vos annonces, Veuillez ressaiser: ");
                    indice=scanner.nextInt();
                    i++;
                    }while(indice>Hote.getAnnonces().size());
                    Hote.getannonce(i-1).supprimerDisponibilite(conn);
                    System.out.println("\n--------------------");
                    break;
                    
                case 0:
                    System.out.println("\n\nA la prochaine " + Hote.getFullName());
                    System.out.println("=== Logging out... ===");
                    return;

                default:
                    System.out.println("Invalid option. Please enter a number between 0 and 6.");
                    break;
            }
        }
    }
    
    public static void menuModification(Connection conn,hote h) {
        Scanner scanner = new Scanner(System.in);
        int choice,id;
        String query = "SELECT id_locataire FROM hote WHERE id_hote = ?";
        try (PreparedStatement stmt = conn.prepareStatement(query)) {
            stmt.setInt(1, h.getid());
            ResultSet rs = stmt.executeQuery();
            if (rs.next()) {
                id= rs.getInt("id_locataire");
            } else {
                System.out.println("Aucun hôte trouvé avec cet ID.");
                return;
            }
        } catch (SQLException e) {
            System.err.println("Erreur SQL: " + e.getMessage());
            return;
        }
        
        while (true) {
            System.out.println("\n--- MENU MODIFICATION ---");
            System.out.println("1. Modifier Nom");
            System.out.println("2. Modifier Prenom");
            System.out.println("3. Modifier Email");
            System.out.println("4. Modifier Telephone");
            System.out.println("5. Modifier Mot de Passe");
            System.out.println("6. Quitter Modification");
            System.out.print("Entrer votre Choix: ");

            if (!scanner.hasNextInt()) {
                scanner.nextLine(); // clear buffer
                System.out.println("Entrée invalide. Veuillez entrer un nombre entre 1 et 6.");
                continue;
            }

            choice = scanner.nextInt();
            scanner.nextLine(); // consume newline

            switch (choice) {
                case 1:
                    System.out.println("Modification de Nom.");
                    h.modifierNom(conn, scanner,id);
                    break;
                case 2:
                    System.out.println("Modification de Prenom.");
                    h.modifierPrenom(conn, scanner,id);
                    break;
                case 3:
                    System.out.println("Modification d'Email.");
                    h.modifierEmail(conn, scanner,id);
                    break;
                case 4:
                    System.out.println("Modification de Telephone.");
                    h.modifierTel(conn, scanner,id);
                    break;
                case 5:
                    System.out.println("Modification de Mot de Passe.");
                    h.modifierMdp(conn, scanner,id);
                    break;
                case 6:
                    System.out.println("-----------------------------");
                    return;
                default:
                    System.out.println("Choix invalide.");
            }
        }
    }
    public static void pause() {
    	Scanner scanner = new Scanner(System.in);
    	System.out.print("Appuyez sur Enter pour continuer...");
        scanner.nextLine();
    }
   
}


