<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes for gigs table
        Schema::table('gigs', function (Blueprint $table) {
            $table->index(['user_id', 'status'], 'idx_gigs_user_status');
            $table->index(['category_id', 'status'], 'idx_gigs_category_status');
            $table->index(['status', 'created_at'], 'idx_gigs_status_created');
            $table->index('slug', 'idx_gigs_slug');
            $table->index('featured', 'idx_gigs_featured');
            $table->index(['price', 'status'], 'idx_gigs_price_status');
        });

        // Add indexes for bookings table
        Schema::table('bookings', function (Blueprint $table) {
            $table->index(['gigs_id', 'created_at'], 'idx_bookings_gigs_created');
            $table->index(['user_id', 'status'], 'idx_bookings_user_status');
            $table->index(['seller_id', 'status'], 'idx_bookings_seller_status');
            $table->index(['status', 'created_at'], 'idx_bookings_status_created');
            $table->index('order_number', 'idx_bookings_order_number');
        });

        // Add indexes for users table
        Schema::table('users', function (Blueprint $table) {
            $table->index(['email', 'status'], 'idx_users_email_status');
            $table->index(['user_type', 'status'], 'idx_users_type_status');
            $table->index('role_id', 'idx_users_role');
        });

        // Add indexes for categories table
        Schema::table('categories', function (Blueprint $table) {
            $table->index(['parent_id', 'status'], 'idx_categories_parent_status');
            $table->index('slug', 'idx_categories_slug');
        });

        // Add indexes for reviews table (if exists)
        if (Schema::hasTable('reviews')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->index(['gigs_id', 'status'], 'idx_reviews_gigs_status');
                $table->index(['user_id', 'created_at'], 'idx_reviews_user_created');
                $table->index(['rating', 'status'], 'idx_reviews_rating_status');
            });
        }

        // Add indexes for notifications table
        if (Schema::hasTable('notifications')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->index(['user_id', 'read_at'], 'idx_notifications_user_read');
                $table->index(['user_id', 'created_at'], 'idx_notifications_user_created');
            });
        }

        // Add indexes for general_settings table
        Schema::table('general_settings', function (Blueprint $table) {
            $table->index(['group_id', 'key'], 'idx_settings_group_key');
            $table->index('key', 'idx_settings_key');
        });

        // Add indexes for permissions table
        if (Schema::hasTable('permissions')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->index(['role_id', 'module_id'], 'idx_permissions_role_module');
            });
        }

        // Add indexes for user_details table (if exists)
        if (Schema::hasTable('user_details')) {
            Schema::table('user_details', function (Blueprint $table) {
                $table->index('user_id', 'idx_user_details_user');
            });
        }

        // Add indexes for gigs_meta table (if exists)
        if (Schema::hasTable('gigs_meta')) {
            Schema::table('gigs_meta', function (Blueprint $table) {
                $table->index(['gig_id', 'key'], 'idx_gigs_meta_gig_key');
            });
        }

        // Add indexes for email_templates table (if exists)
        if (Schema::hasTable('email_templates')) {
            Schema::table('email_templates', function (Blueprint $table) {
                $table->index(['notification_type', 'status'], 'idx_email_templates_type_status');
            });
        }

        // Add indexes for currencies table (if exists)
        if (Schema::hasTable('currencies')) {
            Schema::table('currencies', function (Blueprint $table) {
                $table->index(['code', 'status'], 'idx_currencies_code_status');
                $table->index('status', 'idx_currencies_status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes for gigs table
        Schema::table('gigs', function (Blueprint $table) {
            $table->dropIndex('idx_gigs_user_status');
            $table->dropIndex('idx_gigs_category_status');
            $table->dropIndex('idx_gigs_status_created');
            $table->dropIndex('idx_gigs_slug');
            $table->dropIndex('idx_gigs_featured');
            $table->dropIndex('idx_gigs_price_status');
        });

        // Drop indexes for bookings table
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex('idx_bookings_gigs_created');
            $table->dropIndex('idx_bookings_user_status');
            $table->dropIndex('idx_bookings_seller_status');
            $table->dropIndex('idx_bookings_status_created');
            $table->dropIndex('idx_bookings_order_number');
        });

        // Drop indexes for users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_email_status');
            $table->dropIndex('idx_users_type_status');
            $table->dropIndex('idx_users_role');
        });

        // Drop indexes for categories table
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('idx_categories_parent_status');
            $table->dropIndex('idx_categories_slug');
        });

        // Drop indexes for reviews table (if exists)
        if (Schema::hasTable('reviews')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->dropIndex('idx_reviews_gigs_status');
                $table->dropIndex('idx_reviews_user_created');
                $table->dropIndex('idx_reviews_rating_status');
            });
        }

        // Drop indexes for notifications table
        if (Schema::hasTable('notifications')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->dropIndex('idx_notifications_user_read');
                $table->dropIndex('idx_notifications_user_created');
            });
        }

        // Drop indexes for general_settings table
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropIndex('idx_settings_group_key');
            $table->dropIndex('idx_settings_key');
        });

        // Drop indexes for permissions table
        if (Schema::hasTable('permissions')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->dropIndex('idx_permissions_role_module');
            });
        }

        // Drop indexes for user_details table (if exists)
        if (Schema::hasTable('user_details')) {
            Schema::table('user_details', function (Blueprint $table) {
                $table->dropIndex('idx_user_details_user');
            });
        }

        // Drop indexes for gigs_meta table (if exists)
        if (Schema::hasTable('gigs_meta')) {
            Schema::table('gigs_meta', function (Blueprint $table) {
                $table->dropIndex('idx_gigs_meta_gig_key');
            });
        }

        // Drop indexes for email_templates table (if exists)
        if (Schema::hasTable('email_templates')) {
            Schema::table('email_templates', function (Blueprint $table) {
                $table->dropIndex('idx_email_templates_type_status');
            });
        }

        // Drop indexes for currencies table (if exists)
        if (Schema::hasTable('currencies')) {
            Schema::table('currencies', function (Blueprint $table) {
                $table->dropIndex('idx_currencies_code_status');
                $table->dropIndex('idx_currencies_status');
            });
        }
    }
};