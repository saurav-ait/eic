<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


    class Services extends Model {

        protected $fillable = ['name', 'description'];

        public function categories() {
            return $this->hasMany(Category::class);
        }
        public function subcategories() {
            return $this->hasMany(Subcategory::class);
        }
    }

    // Category.php
    class Category extends Model {
        public function service() {
            return $this->belongsTo(Services::class);
        }
    }

    // Subcategory.php
    class Subcategory extends Model {
        public function service() {
            return $this->belongsTo(Services::class);
        }
    }
