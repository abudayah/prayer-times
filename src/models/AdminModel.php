<?php

namespace Isbc\Prayertimes\models;

class AdminModel {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getImages() {
        return $this->db->exec('SELECT * FROM posters ORDER BY timestamp DESC');
    }

    public function getPublishedImages() {
        return $this->db->exec('SELECT * FROM posters WHERE published = 1 ORDER BY timestamp DESC');
    }

    public function saveImage($name, $imagePath) {
        return $this->db->exec(
            'INSERT INTO posters (name, file_path, published) VALUES (?, ?, 1)',
            [$name, $imagePath]
        );
    }

    public function getImagePath($id) {
        $image = $this->db->exec('SELECT file_path FROM posters WHERE id = ?', $id);
        if (!empty($image)) {
            return $image[0]['file_path'];
        }
        return false;
    }

    public function deleteImage($id) {
        return $this->db->exec('DELETE FROM posters WHERE id = ?', $id);
    }

    public function togglePublish($id) {
        return $this->db->exec(
            'UPDATE posters SET published = NOT published WHERE id = ?',
            [$id]
        );
    }

    public function getPublishedStatus($id) {
        $result = $this->db->exec('SELECT published FROM posters WHERE id = ?', $id);
        return !empty($result) ? (bool) $result[0]['published'] : false;
    }

}
