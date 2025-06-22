import java.util.ArrayList;
import java.util.List;

public class locataire extends user {
    private int id;

    // Relationships
    private List<reservation> reservations;

    // Constructor

    public locataire() {
        super();
        this.reservations = new ArrayList<>();
    }

    public locataire(int id,String username, String nom,String prenom, String email, String mot_de_passe, String numero_de_telephone) {
        super(id, username,prenom, nom, email, mot_de_passe, numero_de_telephone);
        this.reservations = new ArrayList<>();
    }

    public locataire(String username, String nom,String prenom, String email, String mot_de_passe, String numero_de_telephone) {
        super(username,prenom, nom, email, mot_de_passe, numero_de_telephone);
        this.reservations = new ArrayList<>();
    }

    // Methods


    // Getters and Setters
    public int getId() {
        return id;
    }

    public void setId( int id) {
        this.id = id;
    }

    public List<reservation> getReservations() {
        return reservations;
    }

    @Override
    public String toString() {
        return "Locateire (" + username +", " + nom+", "+ prenom +","+ email +", "+ password+")";
    }
}
