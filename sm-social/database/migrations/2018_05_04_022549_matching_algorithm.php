<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MatchingAlgorithm extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */

    const TABLE_NAME_CUSTOM_CRITERION_WEIGHT = 'custom_criterion_weight';
    const TABLE_NAME_WEIGHTS                 = 'weights';
    const TABLE_NAME_ANSWER_QUESTION_WEIGHT  = 'answer_question_weight';
    const TABLE_NAME_QUESTIONS_CATEGORIES    = 'questions_categories';
    const TABLE_NAME_USER_ANSWER_QUESTION    = 'user_answer_question';
    const TABLE_NAME_ANSWER_QUESTION         = 'answer_question';
    const TABLE_NAME_ANSWER_SET              = 'answer_set';
    const TABLE_NAME_ANSWER_SETS             = 'answer_sets';
    const TABLE_NAME_ANSWERS_WORDING         = 'answers_wording';
    const TABLE_NAME_CRITERIA                = 'criteria';

    public function up()
    {
        /**
         * Questions, Answers, Answer Sets, Categories, and Weights
         */

        /** Question Title */
        Schema::dropIfExists(self::TABLE_NAME_CUSTOM_CRITERION_WEIGHT);
        Schema::dropIfExists(self::TABLE_NAME_WEIGHTS);
        Schema::dropIfExists(self::TABLE_NAME_ANSWER_QUESTION_WEIGHT);
        Schema::dropIfExists(self::TABLE_NAME_QUESTIONS_CATEGORIES);
        Schema::dropIfExists(self::TABLE_NAME_USER_ANSWER_QUESTION);
        Schema::dropIfExists(self::TABLE_NAME_ANSWER_QUESTION);
        Schema::dropIfExists(self::TABLE_NAME_ANSWER_SET);
        Schema::dropIfExists(self::TABLE_NAME_ANSWER_SETS);
        Schema::dropIfExists(self::TABLE_NAME_ANSWERS_WORDING);
        Schema::dropIfExists(self::TABLE_NAME_CRITERIA);
        Schema::create(
            self::TABLE_NAME_CRITERIA, function (Blueprint $table) {
            $table->increments('id');
            $table->string('question')->unique();
            $table->enum('question_type', ['mutually_exclusive', 'range']);

        });

        /** Possible Answers to Questions */
        Schema::create(
            self::TABLE_NAME_ANSWERS_WORDING, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('answer_wording')->unique();
        });

        /** Title of Answers Sets */
        Schema::create(
            self::TABLE_NAME_ANSWER_SETS, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('set_title')->unique();

        });

        /** Actual Pivot Table for Answer & Answer Set*/
        Schema::create(
            self::TABLE_NAME_ANSWER_SET, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->unsignedInteger('set')->nullable();
            $table->unsignedInteger('answer')->nullable();
            $table->primary(['set', 'answer']);
        });

        Schema::table(self::TABLE_NAME_ANSWER_SET, function (Blueprint $table) {
            $table->foreign('set')->references('id')->on(self::TABLE_NAME_ANSWER_SETS);
            $table->foreign('answer')->references('id')->on(self::TABLE_NAME_ANSWERS_WORDING);
        });

        /** answers that belong  to which question */
        Schema::create(
            self::TABLE_NAME_ANSWER_QUESTION, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('thumbprint')->nullable()->unique();
            $table->unsignedInteger('answer')->nullable();
            $table->unsignedInteger('question')->nullable();
            $table->string('attribute_name')->unique();
            $table->primary(['answer', 'question']);

        });

        Schema::table(self::TABLE_NAME_ANSWER_QUESTION, function (Blueprint $table) {
            $table->foreign('answer')->references('id')->on(self::TABLE_NAME_ANSWERS_WORDING);
            $table->foreign('question')->references('id')->on(self::TABLE_NAME_CRITERIA);
        });

        /** the actual answers from the user to each question, pivot-table */
        Schema::create(
            self::TABLE_NAME_USER_ANSWER_QUESTION, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('answer_question_id')->references('thumbprint')
                  ->on(self::TABLE_NAME_ANSWER_QUESTION)
                  ->onDelete('cascade');

            $table->unsignedInteger('answer_question_id')->nullable();
            $table->timestamps();

        });
        Schema::table(self::TABLE_NAME_USER_ANSWER_QUESTION, function (Blueprint $table) {
            $table->primary(['answer_question_id', 'user_id']);
//            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });

        /** Titles of Categories */
        Schema::create(
        self::TABLE_NAME_QUESTIONS_CATEGORIES, function (Blueprint $table) {
            $table->engine = 'InnoDB';
        $table->increments('id');
            $table->string('category_title')->unique();
        });

        /** Weights of answers from within each question*/
        Schema::create(
            self::TABLE_NAME_ANSWER_QUESTION_WEIGHT, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('answer')->nullable();
            $table->double('weight');
        });
        Schema::table(self::TABLE_NAME_ANSWER_QUESTION_WEIGHT, function (Blueprint $table) {
            $table->foreign('answer')->references('thumbprint')->on(self::TABLE_NAME_ANSWER_QUESTION);

        });


        /**
         * Tags or named attributes of individual or categorized questions
         * Adds up answers in each category [group of questions]
         * and gives a tag based on that
         */

        Schema::create(
            self::TABLE_NAME_WEIGHTS, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->double('inequality_less_than_weight_value');
            $table->double('inequality_greater_than_weight_value');

            //-1 for no category
            $table->unsignedInteger('category')->nullable();
            $table->string('description')->unique();
        });
        Schema::table(self::TABLE_NAME_WEIGHTS, function (Blueprint $table) {
            $table->foreign('category')->references('id')->on(self::TABLE_NAME_QUESTIONS_CATEGORIES);

        });


        /** For each user, they can choose to make an answer more important and it will save that here */
        Schema::create(
            self::TABLE_NAME_CUSTOM_CRITERION_WEIGHT, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('answer')->nullable();
            $table->double('weight');
            $table->timestamps();
            $table->primary(['answer', 'user_id']);
        });
        Schema::table(self::TABLE_NAME_CUSTOM_CRITERION_WEIGHT, function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('answer')->references('thumbprint')->on(self::TABLE_NAME_ANSWER_QUESTION);
        });


    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists(self::TABLE_NAME_CUSTOM_CRITERION_WEIGHT);
        Schema::dropIfExists(self::TABLE_NAME_WEIGHTS);
        Schema::dropIfExists(self::TABLE_NAME_ANSWER_QUESTION_WEIGHT);
        Schema::dropIfExists(self::TABLE_NAME_QUESTIONS_CATEGORIES);
        Schema::dropIfExists(self::TABLE_NAME_USER_ANSWER_QUESTION);
        Schema::dropIfExists(self::TABLE_NAME_ANSWER_QUESTION);
        Schema::dropIfExists(self::TABLE_NAME_ANSWER_SET);
        Schema::dropIfExists(self::TABLE_NAME_ANSWER_SETS);
        Schema::dropIfExists(self::TABLE_NAME_ANSWERS_WORDING);
        Schema::dropIfExists(self::TABLE_NAME_CRITERIA);
    }
}
