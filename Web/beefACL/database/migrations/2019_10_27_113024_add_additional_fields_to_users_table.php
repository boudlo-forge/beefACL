<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->text('legal_name')->nullable();
            $table->unsignedInteger('contact_me')->nullable();

            $table->text('address_1')->nullable();
            $table->text('address_2')->nullable();
            $table->text('address_3')->nullable(); // town / region / parish
            $table->text('post_code')->nullable();

            $table->text('phone')->nullable();
            $table->text('phone_2')->nullable();

            $table->text('emergency_contact_name_1')->nullable();
            $table->text('emergency_contact_phone_1')->nullable();
            $table->text('emergency_contact_name_2')->nullable();
            $table->text('emergency_contact_phone_2')->nullable();

            $table->unsignedInteger('risk_flags')->default(0);
            $table->text('risk_notes')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            
            $table->dropColumn('legal_name');
            $table->dropColumn('contact_me');

            $table->dropColumn('address_1');
            $table->dropColumn('address_2');
            $table->dropColumn('address_3');
            $table->dropColumn('post_code');

            $table->dropColumn('phone');
            $table->dropColumn('phone_2');

            $table->dropColumn('emergency_contact_name_1');
            $table->dropColumn('emergency_contact_phone_1');
            $table->dropColumn('emergency_contact_name_2');
            $table->dropColumn('emergency_contact_phone_2');

            $table->dropColumn('risk_flags');
            $table->dropColumn('risk_notes');

        });
    }
}
