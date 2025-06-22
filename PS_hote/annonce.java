import java.util.Date;
import java.util.ArrayList;
import java.util.List;
import java.util.Scanner;
import java.sql.*;
import java.text.SimpleDateFormat;




public class annonce {
    private int id;
    private String titre;
    private String description;
    private String adress;
    private String ville;
    public enum Type{  Appartement, Maison, Studio , Chambre};
    private Type type;
    private List<Date> disponibilite;
    private List<Date> Dispo;
    private double Prx_Nuit;


    private List<String> photos;
    private List<avis> avis;
    private List<reservation> reservation;
    private hote Hote;


    public annonce() {
        this.avis = new ArrayList<>();
        this.reservation = new ArrayList<>();
        this.photos = new ArrayList<>();
        this.Dispo = new ArrayList<>();
    }

    
    public annonce(Connection conn,int id, String titre, String description, String adress,double Prx_Nuit,Type type, String ville) {
        this.id = id;
        this.titre = titre;
        this.description = description;
        this.adress = adress;
        this.type = type;
        this.Prx_Nuit= Prx_Nuit;
        this.ville=ville;
        
        this.Dispo = new ArrayList<>();
        remplirDisponibilities(conn);
        this.reservation = new ArrayList<>();
        remplirReservations(conn);
        this.avis = new ArrayList<>();
        remplirAvis(conn);
    }
    
    public annonce( String titre, String description, String adress, String ville, Type type, List<String> photo, hote Hote,double prix) {
        this.titre = titre;
        this.description = description;
        this.adress = adress;
        this.ville = ville;
        this.type = type;
        this.photos = new ArrayList<>();
        this.photos.addAll(photo);
        this.Hote = Hote;
        this.Dispo = new ArrayList<>();
        this.avis = new ArrayList<>();
        this.reservation = new ArrayList<>();
        this.Prx_Nuit=prix;
    }

    public boolean ajout_Disponibilite(List<Date> newDates) {
        if (newDates == null || newDates.isEmpty()) {
            System.out.println("Les nouvelles dates ne peuvent pas être nulles ou vides.");
            return false;
        }

        if (disponibilite == null) {
            disponibilite = new ArrayList<>();
        }

        for (Date newDate : newDates) {
            if (!disponibilite.contains(newDate)) {
                disponibilite.add(newDate);
            }
        }

        //Sort the existing dates in ascending order
        for (int i = 0; i < disponibilite.size() - 1; i++) {
            for (int j = 0; j < disponibilite.size() - 1 - i; j++) {
                Date current = disponibilite.get(j);
                Date next = disponibilite.get(j + 1);

                if (current.after(next)) {
                    disponibilite.set(j, next);
                    disponibilite.set(j + 1, current);
                }
            }
        }
        updateDisponibilite();
        System.out.println("Disponibilités ajoutées avec succès.");
        return true;
    }


    public void updateDisponibilite() {
        if (disponibilite == null) {
            disponibilite = new ArrayList<>();
        }

        Date today = new Date();
        System.out.println("Date d'aujourd'hui : " + today);

        for (int i = disponibilite.size() - 1; i >= 0; i--) {
            Date date = disponibilite.get(i);
            if (date.before(today)) {
                disponibilite.remove(i);
                System.out.println("Test");
            }
        }

        System.out.println("Disponibilités mises à jour.");
    }

    public boolean delete_disponibilite(Date date) {
        if (disponibilite != null && disponibilite.contains(date)) {
            disponibilite.remove(date);
            System.out.println("Disponibilité supprimée avec succès.");
            return true;
        } else {
            System.out.println("La date spécifiée n'est pas dans la liste des disponibilités.");
        }
        return false;
    }


    public void afficherDisponibilite() {
        if (disponibilite == null || disponibilite.isEmpty()) {
            System.out.println("Aucune disponibilité n'est enregistrée.");
            return;
        }

        System.out.println("Jours disponibles :");
        for (Date date : disponibilite) {
            System.out.println(" - " + date);
        }
    }



    public void ajout_avis( String commentaire, int note, user utilisateur) {
        avis avis = new avis( commentaire, note, utilisateur, this);
        this.avis.add(avis);
    }

    //TODO : Modifier disponibilité
    //TODO : supprimer disponibilité



    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getTitre() {
        return titre;
    }

    public void setTitre(String titre) {
        this.titre = titre;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public Type getType() {
        return type;
    }

    public void setType(Type type) {
        this.type = type;
    }

    public List<String> getPhoto() {
        return photos;
    }

    public void setPhoto(List<String> photo) {
        this.photos = photo;
    }

    public void setHote(hote hote) {
        this.Hote = hote;
    }

    public hote getHote() {
        return Hote;
    }

    public String getAdress() {
        return adress;
    }

    public String getVille() {
        return ville;
    }

    public List <Date> getDisponibilite() {
        return disponibilite;
    }

    public void setAdress(String adress) {
        this.adress = adress;
    }

    public void setVille(String ville) {
        this.ville = ville;
    }

    public void setDisponibilite(List<Date> disponibilite) {
        this.disponibilite = disponibilite;
    }

    public List<avis> getAvis() {
        return avis;
    }

    public void setAvis(List<avis> avis) {
        this.avis = avis;
    }

    public List<reservation> getReservation() {
        return reservation;
    }

    public void setReservation(List<reservation> reservation) {
        this.reservation = reservation;
    }

    public void afficherAnnonce() {
    	char choix;
    	Scanner scanner = new Scanner(System.in);
        System.out.println("/------------  Annonce  ------------\\");
        //System.out.println("ID Annonce: " + this.id);
        System.out.println("Titre: " + this.titre);
        System.out.println("Description: " + this.description);
        System.out.println("Prix par Nuit: " + this.Prx_Nuit+"DH");
        System.out.println("Type de Propriete: " + this.type);
        System.out.println("Ville: " + this.ville);
        if (this.Dispo.size() > 0) { 
        System.out.println("Les dates ou l'annonce est indisponible: ");
        afficherDisponibilities();}
        if (this.reservation.size() > 0) { 
        System.out.println("Les reservations : ");
        afficherReservations();}
        if (this.avis.size() > 0) { 
        System.out.println("Voullez vous voir les avis de cette annonce?(y/n) : ");
        int i=0;
        do {
        	if(i>0) {
        		System.out.println("Veuillez reessayer, le choix doit etre 'y' ou 'n' : ");
        		}
        	choix = scanner.next().charAt(0);
        	i++;
        }while(choix!='y' && choix!='n' && choix!='Y' && choix!='N');
        if(choix=='y' || choix=='Y') afficherAvis();}
        System.out.println("\\----------------- -----------------/");
    }
    
    public void remplirDisponibilities(Connection conn) {
        String query = "SELECT d.date_dispo " +
                       "FROM annonce an JOIN disponibilite d ON an.id_annonce = d.id_annonce " +
                       "WHERE an.id_annonce = ?";  // Using parameterized query to prevent SQL injection

        try (PreparedStatement ps = conn.prepareStatement(query)) {
            // Set the ID_Annonce parameter in the query
            ps.setInt(1, this.id);

            try (ResultSet rs = ps.executeQuery()) {
                while (rs.next()) {
                	Date dispo = rs.getDate("date_dispo");  // Retrieve the date from the result set
                    if (dispo != null) {
                        this.Dispo.add(dispo);  // Add the date to the list
                    }
                }
            }
        } catch (SQLException e) {
            System.err.println("Erreur lors du chargement des disponibilités : " + e.getMessage());
        }
    }
    public void afficherDisponibilities() {
        SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd");
        for (int i = 0; i < this.Dispo.size(); i++) {
            System.out.println("\t '" + sdf.format(this.Dispo.get(i)) + "'");
        }
        System.out.println();
    }
    
    public void remplirReservations(Connection conn) {
        String query = "SELECT r.id_reservation, r.date_debut, r.date_fin, l.nom, l.prenom ,r.montant " +
                       "FROM locataire l " +
                       "JOIN reservation r ON l.id_locataire = r.id_locataire " +
                       "JOIN annonce an ON r.id_annonce = an.id_annonce " +
                       "WHERE an.id_annonce = ?";  // Using parameterized query to prevent SQL injection

        try (PreparedStatement ps = conn.prepareStatement(query)) {
            // Set the ID_Annonce parameter in the query
            ps.setInt(1, this.id);  // Assuming 'this.id' refers to the current 'Annonce' object

            try (ResultSet rs = ps.executeQuery()) {
                while (rs.next()) {
                    int idReservation = rs.getInt("id_reservation");
                    Date dateDebut = rs.getDate("date_debut");
                    Date dateFin = rs.getDate("date_fin");
                    String nom = rs.getString("nom");
                    String prenom = rs.getString("prenom");
                    nom += " " + prenom;  // Concatenate name and surname
                    double montant = rs.getDouble("montant");
                    // Create Reservation object from query results
                    reservation reservation = new reservation(idReservation, dateDebut, dateFin, nom,montant);

                    // Add the Reservation to the list
                    this.reservation.add(reservation);
                }
            }
        } catch (SQLException e) {
            System.err.println("Erreur lors du chargement des réservations : " + e.getMessage());
        }
    }
    public void afficherReservations() {
        for (int i = 0; i < reservation.size(); i++) {
            reservation.get(i).afficherReservation();
        }
        System.out.println();
    }
    
    public void remplirAvis(Connection conn) {
        String query = "SELECT av.id_avis, l.nom, l.prenom, av.note, av.commentaire, av.created_at " +
                       "FROM locataire l " +
                       "JOIN avis av ON l.id_locataire = av.id_locataire " +
                       "JOIN annonce an ON an.id_annonce = av.id_annonce " +
                       "WHERE an.id_annonce = ?";

        try (PreparedStatement ps = conn.prepareStatement(query)) {
            ps.setInt(1, this.id); // Assuming `this.id` is the ID_Annonce

            try (ResultSet rs = ps.executeQuery()) {
                while (rs.next()) {
                    int idAvis = rs.getInt("id_avis");
                    String nom = rs.getString("prenom") + " " + rs.getString("nom");
                    int note = rs.getInt("note");
                    String commentaire = rs.getString("commentaire");
                    Date date = rs.getDate("created_at"); // Or rs.getDate(...).toString()

                    avis avisObj = new avis(idAvis, nom, note, commentaire, date);

                    this.avis.add(avisObj); // Add to the list
                }
            }
        } catch (SQLException e) {
            System.err.println("Erreur lors du chargement des avis : " + e.getMessage());
        }
    }
    public void afficherAvis() {
        for (int i = 0; i < avis.size(); i++) {
            avis.get(i).afficherAvis();
            mainnn.pause();
        }
        System.out.println();
    }
    public void ajouterDisponibilite(Connection conn) {
        Scanner scanner = new Scanner(System.in);

        System.out.println("=== Ajouter Disponibilité ===");

        // Ask the user for the start and end dates
        System.out.print("Entrez la date de début (format: yyyy-MM-dd): ");
        String startDateString = scanner.nextLine();
        System.out.print("Entrez la date de fin (format: yyyy-MM-dd): ");
        String endDateString = scanner.nextLine();

        // Parse the input dates
        SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd");
        java.util.Date startDate = null;
        java.util.Date endDate = null;

        try {
            startDate = sdf.parse(startDateString);
            endDate = sdf.parse(endDateString);
        } catch (Exception e) {
            System.out.println("Erreur dans le format de la date. Veuillez utiliser le format yyyy-MM-dd.");
            return;
        }

        // Check if the start date is before the end date
        if (startDate.after(endDate)) {
            System.out.println("La date de début ne peut pas être après la date de fin.");
            return;
        }

        // Insert each date between the start and end date into the database
        String insertDisponibiliteQuery = "INSERT INTO disponibilite (date_dispo, id_annonce) VALUES (?, ?)";
        String checkDisponibiliteQuery = "SELECT 1 FROM disponibilite WHERE id_annonce = ? AND date_dispo = ?";

        try (PreparedStatement checkPs = conn.prepareStatement(checkDisponibiliteQuery);
             PreparedStatement ps = conn.prepareStatement(insertDisponibiliteQuery)) {

            while (!startDate.after(endDate)) {
                String formattedDate = sdf.format(startDate); // Format the date to string

                // Check if the date already exists for this id_annonce
                checkPs.setInt(1, this.id);
                checkPs.setString(2, formattedDate);
                ResultSet rs = checkPs.executeQuery();

                if (!rs.next()) {
                    // Date does not exist, so insert it into the database
                    ps.setString(1, formattedDate); // Set the date as string in SQL
                    ps.setInt(2, this.id);
                    ps.executeUpdate(); // Insert the date into the database
                    System.out.println("Disponibilité ajoutée pour la date: " + formattedDate);

                    // Add the date to the dispo list (as Date object)
                    this.Dispo.add(new java.sql.Date(startDate.getTime()));
                } else {
                    System.out.println("La disponibilité pour la date " + formattedDate + " existe déjà.");
                }

                // Move to the next day
                startDate.setTime(startDate.getTime() + 24 * 60 * 60 * 1000); // Add one day
            }
            System.out.println("Toutes les disponibilités ont été vérifiées.");
        } catch (SQLException e) {
            System.err.println("Erreur SQL lors de l'ajout des disponibilités : " + e.getMessage());
        }
    }
    
    public void supprimerDisponibilite(Connection conn) {
        Scanner scanner = new Scanner(System.in);

        System.out.println("=== Supprimer Disponibilité ===");

        System.out.print("Entrez la date de début (format: yyyy-MM-dd): ");
        String startDateString = scanner.nextLine();
        System.out.print("Entrez la date de fin (format: yyyy-MM-dd): ");
        String endDateString = scanner.nextLine();

        SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd");
        java.util.Date startDate = null;
        java.util.Date endDate = null;

        try {
            startDate = sdf.parse(startDateString);
            endDate = sdf.parse(endDateString);
        } catch (Exception e) {
            System.out.println("Erreur dans le format de la date. Veuillez utiliser le format yyyy-MM-dd.");
            return;
        }

        if (startDate.after(endDate)) {
            System.out.println("La date de début ne peut pas être après la date de fin.");
            return;
        }

        String deleteDisponibiliteQuery = "DELETE FROM disponibilite WHERE id_annonce = ? AND date_dispo = ?";

        try (PreparedStatement ps = conn.prepareStatement(deleteDisponibiliteQuery)) {

            while (!startDate.after(endDate)) {
                java.sql.Date sqlDate = new java.sql.Date(startDate.getTime());
                String formattedDate = sdf.format(startDate);

                ps.setInt(1, this.id);
                ps.setDate(2, sqlDate);
                int rowsAffected = ps.executeUpdate();

                if (rowsAffected > 0) {
                    System.out.println("Disponibilité supprimée pour la date: " + formattedDate);
                    if (this.Dispo != null) {
                        this.Dispo.removeIf(d -> sdf.format(d).equals(formattedDate));
                    }
                } else {
                    System.out.println("Aucune disponibilité trouvée pour la date: " + formattedDate);
                }

                startDate.setTime(startDate.getTime() + 24 * 60 * 60 * 1000); // +1 day
            }

            System.out.println("Suppression terminée.");
        } catch (SQLException e) {
            System.err.println("Erreur SQL lors de la suppression des disponibilités : " + e.getMessage());
        }
    }

}
