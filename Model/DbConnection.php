<?php

  // Loaded all required libraries.
  require("../vendor/autoload.php");

  // Loading .env credentials.
  $dotEnv = Dotenv\Dotenv::createImmutable(__DIR__);
  $dotEnv->safeLoad();

  /**
   * Connects with database using mysqli, and provide more functionality to for database.
   * 
   *  @method existsUserId
   *    Checks if user's user_id exists.
   * 
   *  @method existsMailId
   *    Checks if user's mail id exists. 
   * 
   *  @method getQueryArray
   *    Sends query response as array
   * 
   *  @method getPhotoURLbyId
   *    Get photo url of user by id. 
   * 
   *  @method getUsersCount
   *    Get counts of all users. 
   * 
   *  @method getPhotoURLbyUserId
   *    Get photo url of user by user id. 
   * 
   *  @method getPass
   *    Get Password of given userId.
   * 
   *  @method getName
   *    Get name of given userId.
   * 
   *  @method getNamebyId
   *    Get name of given ID. 
   * 
   *  @method getId
   *    Get Id of given userId. 
   * 
   *  @method getAbout
   *    Get About of given userId. 
   * 
   *  @method getAllUsers
   *    Get all users of ordered by given order, by default it order by first_name.
   * 
   *  @method getAllPosts
   *    Get limit number of posts of ordered by given order.
   * 
   *  @method getOnline
   *    Get online status of given userId. 
   * 
   *  @method getLikeCountbyPostId
   *    Get like count of given postId. 
   * 
   *  @method getLikedPostId
   *    Get postIds of liked posts only.
   * 
   *  @method getPostIdbyUserId
   *    Get post id of given userId as string. 
   * 
   *  @method setPost
   *    Set new created post to database. 
   * 
   *  @method setUser
   *    Sets users details. 
   * 
   *  @method setOnline
   *    Update online status of given user id.
   * 
   *  @method deletePost
   *    Delete post of given postId. 
   * 
   *  @var mysqli $conn
   *    Update online status of given user id. 
   */
  class DbConnection {
    /**
     * @var object
     *  Define global $conn to hold connection object after successful authentication with database.
     */
    public mysqli $conn;

    /**
     * Creates MySqli object and connect with database.
     * 
     *  @return mysqli object
     *    Returns the connection object of database.
     */
    public function __construct() {
      // Stores the Mysqli connection object. 
      $connect = new mysqli("localhost", $_ENV['sqlUser'], $_ENV['sqlPass'], $_ENV['dbName']);
      if ($connect->connect_error) {
        echo "Connection error:" . $connect->connect_error;
      }
      $this->conn = $connect;
    }

    /**
     * Checks if user's user_id exists. 
     * 
     *  @param string $userId
     *    Stores the user Id string.
     *  
     *  @return bool
     *    Returns TRUE if user exists ,else FALSE.
     */
    public function existsUserId(string $userId) {
      if (isset($this->conn->query("select user_id from `oauth-users` where `user_id`='" . $userId . "'")->fetch_assoc()['user_id'])) {
        return TRUE;
      }
        return FALSE;
    }

    /**
     * Checks if user's mail id exists. 
     * 
     *  @param string $mailId
     *    Stores the mail Id string.
     *  
     *  @return bool
     *    Returns TRUE if mail id exists ,else FALSE.
     */
    public function existsMailId(string $mailId) {
      if (isset($this->conn->query("select email from `oauth-users` where email='" . $mailId . "'")->fetch_assoc()['email'])) {
        return TRUE;
      }
        return FALSE;
    }

    /**
     * Sends query response as array. 
     * 
     *  @param string $query
     *    Stores the query string.
     *  
     *  @return array
     *    Response of the query string.
     */
    public function getQueryArray(string $query) {
      $queryResponse = $this->conn->query($query);
      foreach ($queryResponse as $value) {
        $response[] = $value;
      }
      return $response;
    }

    /**
     * Get photo url of user by id. 
     * 
     *  @param int $id
     *    Stores the id of the user.
     * 
     *  @return string
     *    Returns path of image if user exists ,else empty string.
     */
    public function getPhotoURLbyId(int $id) {
      if (isset($this->conn->query('select photo_url from `oauth-users` where id="' . $id . '"')->fetch_assoc()['photo_url'])) {
        return $this->conn->query('select photo_url from `oauth-users` where id="' . $id . '"')->fetch_assoc()['photo_url'];
      }
        return "";
    }

    /**
     * Get counts of all users. 
     * 
     *  @return int
     *    Returns count if user exists ,else 0.
     */
    public function getUsersCount() {
      if (isset($this->conn->query('select count(*) as count from `oauth-users`')->fetch_assoc()['count'])) {
        return $this->conn->query('select count(*) as count from `oauth-users`')->fetch_assoc()['count'];
      }
        return 0;
    }

    /**
     * Get photo url of user by user id. 
     * 
     *  @param string $userId
     *    Stores the user id of user.
     * 
     *  @return string
     *    Returns path of image if user exists ,else empty string.
     */
    public function getPhotoURLbyUserId(string $userId) {
      if (isset($this->conn->query('select photo_url from `oauth-users` where user_id="' . $userId . '"')->fetch_assoc()['photo_url'])) {
        return $this->conn->query('select photo_url from `oauth-users` where user_id="' . $userId . '"')->fetch_assoc()['photo_url'];
      }
        return "";
    }

    /**
     * Get Password of given userId. 
     * 
     *  @param string $userId
     *    Stores the user Id string.
     *  
     *  @return string
     *    Returns pass if user exists ,else return empty string.
     */
    public function getPass(string $userId) {  
      if ($this->existsMailId($userId) && isset($this->conn->query("select pass from `oauth-users` where email='" . $userId . "'")->fetch_assoc()['pass'])) {
        return $this->conn->query("select pass from `oauth-users` where email='" . $userId . "'")->fetch_assoc()['pass'];
      }
      else if ($this->existsUserId($userId) && isset($this->conn->query("select pass from `oauth-users` where user_id='" . $userId . "'")->fetch_assoc()['pass'])) {
        return $this->conn->query("select pass from `oauth-users` where user_id='" . $userId . "'")->fetch_assoc()['pass'];
      }
        return "";
    }

    /**
     * Get name of given userId. 
     * 
     *  @param string $userId
     *    Stores the user Id string.
     *  
     *  @return string
     *    Returns name if user exists ,else return empty string.
     */
    public function getName(string $userId) {
      if ($this->existsUserId($userId) && isset($this->conn->query("select first_name from `oauth-users` where user_id='" . $userId . "'")->fetch_assoc()['first_name'])) {
        return $this->conn->query('SELECT CONCAT_WS(" ", `first_name`, `last_name`) as `fullname` from `oauth-users` where user_id="' . $userId . '"')->fetch_assoc()['fullname'];
      }
        return "";
    }

    /**
     * Get name of given ID. 
     * 
     *  @param int $id
     *    Stores the Id .
     *  
     *  @return string
     *    Returns Name if ID exists ,else return empty string.
     */
    public function getNamebyId(int $id) {
      if (isset($this->conn->query("select first_name from `oauth-users` where id='" . $id . "'")->fetch_assoc()['first_name'])) {
        return $this->conn->query('SELECT CONCAT_WS(" ", `first_name`, `last_name`) as `fullname` from `oauth-users` where id="' . $id . '"')->fetch_assoc()['fullname'];
      }
        return "";
    }

    /**
     * Get Id of given userId. 
     * 
     *  @param string $userId
     *    Stores the user Id string.
     *  
     *  @return string
     *    Returns ID if user exists ,else return empty string.
     */
    public function getId(string $userId) {
      if ($this->existsUserId($userId) && isset($this->conn->query("select id from `oauth-users` where user_id='" . $userId . "'")->fetch_assoc()['id'])) {
        return $this->conn->query('SELECT id from `oauth-users` where user_id="' . $userId . '"')->fetch_assoc()['id'];
      }
        return "";
    }

    /**
     * Get About of given userId. 
     * 
     *  @param string $userId
     *    Stores the user Id string.
     *  
     *  @return string
     *    Returns about if user exists ,else return empty string.
     */
    public function getAbout(string $userId) {
      if ($this->existsUserId($userId) && isset($this->conn->query("select first_name from `oauth-users` where user_id='" . $userId . "'")->fetch_assoc()['first_name'])) {
        return $this->conn->query('SELECT `about` from `oauth-users` where user_id="' . $userId . '"')->fetch_assoc()['about'];
      }
        return "";
    }

    /**
     * Get all users of ordered by given order, by default it order by first_name.  
     * 
     *  @param string $order
     *    Stores the order string.
     *  
     *  @return mysqli_result
     *    Returns all users ordered by givenr order.
     */
    public function getAllUsers(string $order = "first_name") {
        return $this->conn->query("SELECT * from `oauth-users` ORDER BY " . $order);
    }

    /**
     * Get limit number of posts of ordered by given order. 
     * 
     *  @param string $order
     *    Stores the order string.
     * 
     *  @param int $limit
     *    Stores the limit string.
     *  
     *  @return mysqli_result
     *    Returns limit numbers of posts ordered by given order .
     */
    public function getAllPosts(string $order = "post_id", int $limit = 5) {
        return $this->conn->query("SELECT * from `posts` ORDER BY " . $order . " LIMIT " . $limit);
    }

    /**
     * Get online status of given userId. 
     * 
     *  @param string $userId
     *    Stores the user id string.
     *  
     *  @return string
     *    Online status of user
     */
    public function getOnline(string $userId) {
      return $this->conn->query('select online from `oauth-users` where user_id="' . $userId . '"')->fetch_assoc()['online'];
    }

    /**
     * Get like count of given postId. 
     * 
     *  @param int $postId
     *    Stores the post id string.
     *  
     *  @return int
     *    Like count of posts
     */
    public function getLikeCountbyPostId(int $postId) {
      return $this->conn->query('SELECT count(*) from likes where `post_id`="' . $postId . '"')->fetch_array()[0];
    }

    /**
     * Get postIds of liked posts only. 
     * 
     *  @param int $userId
     *    Stores the user id string.
     *  
     *  @return object
     *    Id's of posts
     */
    public function getLikedPostId(int $userId) {
      return $this->conn->query('SELECT `post_id` from likes WHERE `id`=' . $userId);
    }

    /**
     * Get post id of given userId as string. 
     * 
     *  @param int $userId
     *    Stores the user id string.
     *  
     *  @return string
     *    Array of post ids 
     */
    public function getPostIdbyUserId(int $userId) {
      $str = "";
      foreach ($this->conn->query("SELECT post_id from likes where `id`=" . $userId)->fetch_all() as $r)
        $str .= ($r[0] . ' ');
      return $str;
    }

    /**
     * Set new created post to database. 
     * 
     *  @param int $id
     *    Stores the id of post.
     * 
     *  @param string $content
     *    Stores the content text of post.
     * 
     *  @param string $name
     *    Stores the name of post.
     * 
     *  @param string $audio
     *    Stores the audio link with filename of post.
     * 
     *  @param string $video
     *    Stores the video link with filename of post.
     * 
     *  @param string $photo
     *    Stores the photo link with filename of post.
     */
    public function setPost(int $id, string $content, string $name, string $audio = "", string $video = "", string $photo = ""): void {
      $this->conn->query('INSERT INTO `posts` (id,content,name,audio,video,photo)
    values(' . $id . ',"' . $content . '","' . $this->getNamebyId($id) . '","' . $audio . '","' . $video . '","' . $photo . '")');
    }

    /**
     * Sets users details. 
     * 
     *  @param string $oauth_provider
     *    Stores the oauth provider string.
     *  
     *  @param string $user_id
     *    Stores the user id  string.
     *  
     *  @param string $first_name
     *    Stores the first name string.
     *  
     *  @param string $last_name
     *    Stores the last name string.
     *  
     *  @param string $email
     *    Stores the email string.
     *  
     *  @param string $phone
     *    Stores the phone number string.
     *  
     *  @param string $gender
     *    Stores the gendr string.
     *  
     *  @param string $photo_url
     *    Stores the profile photo string.
     *  
     *  @param string $link
     *    Stores the link of account.
     *  
     *  @param string $modified
     *    Stores the modification date  of account details.
     */
    public function setUser(string $oauth_provider, string $user_id, string $first_name, string $last_name, string $email, string $phone, string $gender, string $photo_url, string $link, string $modified): void {
      $sql = "INSERT INTO `oauth-users` (oauth_provider, user_id , first_name, last_name, email, phone, photo_url, link ,modified ) values('" . $oauth_provider . "','" . $user_id . "','" . $first_name . "','" . $last_name . "','" . $email . "','" . $phone . "','" . $photo_url . "','" . $link . "','" . $modified . "')";
      $this->conn->query($sql);
    }

    /**
     * Update online status of given user id. 
     * 
     *  @param string $userId
     *    Stores the user id string.
     *  
     *  @param string $status
     *    Stores the status string.
     */
    public function setOnline(string $userId, string $status):void {
      $this->conn->query('UPDATE `oauth-users` SET online="' . $status . '" where user_id="' . $userId . '"');
    }

    /**
     * Delete post of given postId. 
     * 
     *  @param int $postId
     *    Stores the psot id string.
     */
    public function deletePost(int $postId):void {
      $this->conn->query('SET FOREIGN_KEY_CHECKS = 0');
      $this->conn->query('DELETE FROM POSTS WHERE post_id="' . $postId . '"');
      $this->conn->query('SET FOREIGN_KEY_CHECKS = 1');
    }
  }
?>
