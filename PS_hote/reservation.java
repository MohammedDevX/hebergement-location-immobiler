import java.util.Date;

public class reservation {
    private int id;
    private Date date_debut;
    private Date date_fin;
    private double commssion_platform;
    private double montant_Hote;
    private String NomLocataire;

    private annonce annonce;
    private locataire locataire;

    // Constructor
    public reservation(int id, Date date_debut, Date date_fin, annonce annonce, locataire locataire) {
        this.id = id;
        this.date_debut = date_debut;
        this.date_fin = date_fin;
        this.annonce = annonce;
        this.locataire = locataire;
        // Calculate total based on number of days and price per night
        long diffInMillies = Math.abs(date_fin.getTime() - date_debut.getTime());
        long diffInDays = diffInMillies / (24 * 60 * 60 * 1000);
//        this.total = annonce.getPrix() * diffInDays;
    }
    public reservation(int id, Date date_debut, Date date_fin,String NomLocataire,double montant_Hote) {
        this.id = id;
        this.date_debut = date_debut;
        this.date_fin = date_fin;
        this.NomLocataire= NomLocataire;
        this.montant_Hote=montant_Hote;
        
    }

    // Methods
    public void confirmer() {
        System.out.println("Réservation confirmée pour: " + this.annonce.getTitre());
    }

    public void annuler() {
        System.out.println("Réservation annulée pour: " + this.annonce.getTitre());
    }
    
    public void afficherReservation() {
        System.out.println("de: " + this.date_debut + "  a: " + this.date_fin + ", par: " 
        		+ this.NomLocataire+" ,d'un montant :"+(this.montant_Hote-(this.montant_Hote*0.03)) + " DH");
    }

}
