<?php

namespace App\Http\Controllers;


use App\User;
use Illuminate\Support\Facades\DB;

class QueryBuilderController extends Controller
{
    public function index()
    {
        // Retrieving All Rows from a Table
        $data['students'] = DB::table('users')->get();
        /*
         * foreach ($users as $user) { echo $user->name; }
         */

        // Retrieving A Single Row / Column from a Table
        $data['single_column'] = DB::table('users')->where('name', 'abul')->first();
        /*
         * echo $user->name;
         */

        // If you don't even need an entire row, you may extract a single value from a record using the value method.
        $data['specific_column_of_a_row'] = DB::table('users')->where('name', 'abul')->value('email');

        // To retrieve a single row by its id column value, use the find method
        $data['find_a_specific_column'] = DB::table('users')->find(3);

        // Retrieving a List of Column Values
        $data['a_list_of_column'] = DB::table('users')->pluck('email');

        // You may also specify a custom key column for the returned Collection
        $data['a_list_of_column'] = DB::table('users')->pluck('Name', 'email');

        // Chunking Results
        DB::table('users')->orderBy('id')->chunk(3, function ($users) {
            $i = 0;
            foreach ($users as $user) {
                //
            }
        });

        // You may stop further chunks from being processed by returning false from the Closure.
        DB::table('users')->orderBy('id')->chunk(100, function ($users) {
            // Process the records...
            return false;
        });

        // If you are updating database records while chunking results, your chunk results could change
        // in unexpected ways. So, when updating records while chunking,
        // it is always best to use the chunkById method instead.
        DB::table('users')->where('name', 'Abul')->chunkById(3, function ($users) {
            foreach ($users as $user) {
                DB::table('users')->where('id', $user->id)->update(['name' => 'Kuddus']);
            }
        });

        // Aggregates
        $data['total_users'] = DB::table('users')->count();
        $data['avg'] = DB::table('users')->where('name', 'Babul')->avg('id');
        $data['max'] = DB::table('users')->max('id');
        $data['is_exist'] = DB::table('users')->where('name', 'Babul')->exists();
        $data['does_not_exist'] = DB::table('users')->where('name', 'Keramot')->doesntExist();

        // Selects
        $data['select_row'] = DB::table('users')->select('name', 'email as user_email')->get();
        $data['distinct_row'] = DB::table('users')->distinct()->get();

        $query = DB::table('users')->select('name');
        $data['add_Select_new_row'] = $query->addSelect('email')->get();

        // Sometimes you may need to use a raw expression in a query.
        $data['row_expression'] = DB::table('users')
            ->select(DB::raw('count(*) as user_count, status'))
            ->where('status', '=', 1)
            ->groupBy('status')
            ->get();

        // The selectRaw method can be used in place of addSelect(DB::raw(...)).
        // This method accepts an optional array of bindings as its second argument
        $data['select_row'] = DB::table('users')->selectRaw('total_credit * ? as total_credit', [1])->get(); // total_credit*1

        // The whereRaw and orWhereRaw methods can be used to inject a raw where clause into your query.
        $data['where_row'] = DB::table('users')->whereRaw('total_credit > IF(total_credit = "140", ?, 100)', [200])->get();

        // The havingRaw and orHavingRaw methods may be used to set a raw string as the value of the having clause.
        $data['havingRow'] = DB::table('users')
            ->select('status', DB::raw('SUM(total_credit) as total_credit'))
            ->groupBy('status')
            ->havingRaw('SUM(total_credit) > ?', [300])
            ->get();

        // The orderByRaw method may be used to set a raw string as the value of the order by clause.
        $data['orderByRaw'] = DB::table('users')->orderByRaw('total_credit - id DESC')->get();

        // The groupByRaw method may be used to set a raw string as the value of the group by clause.
        //  $data['groupByRaw'] = DB::table('users')->select('name', 'email')->groupByRaw('status') ->get();

        // Inner Join
        $data['inner_join'] = DB::table('users')
            ->join('course_teachers', 'users.course_teacher_id', '=', 'course_teachers.id')
            ->join('departments', 'users.department_id', '=', 'departments.id')
            ->select('users.*', 'course_teachers.teacher_name', 'departments.dpt_name')
            ->get();

        // Left Join
        $data['left_join'] = DB::table('users')
            ->leftJoin('course_teachers', 'users.course_teacher_id', '=', 'course_teachers.id')
            ->get();

        // Right Join
        $data['right_join'] = DB::table('users')
            ->rightJoin('course_teachers', 'users.course_teacher_id', '=', 'course_teachers.id')
            ->get();

        // Cross Join Clause
        $data['cross_join'] = DB::table('users')->crossJoin('course_teachers')->get();

        // If you would like to use a "where" style clause on your joins, you may use the where and orWhere methods on a join.
        $data['where_orWhere'] = DB::table('users')
            ->join('course_teachers', function ($join) {
                $join->on('users.course_teacher_id', '=', 'course_teachers.id')
                    ->where('course_teachers.id', '=', 1);
            })
            ->get();

        // Subquery Joins
        $data['subquery_join'] = DB::table('users')
            ->select('name', DB::raw('MAX(id) as last_created_user'))
            ->where('status', 1)
            ->groupBy('status');

//        $data['subquery_join_1'] = DB::table('users')
//            ->joinSub($latestPosts, 'latest_posts',
//                function ($join) {
//                $join->on('users.id', '=', 'latest_posts.user_id');
//            })->get();

        // Unions
        $first = DB::table('users')->whereNull('name');
        $data['union'] = DB::table('users')->whereNull('email')->union($first)->get();

        // Simple Where Clauses
        $data['simple_where'] = DB::table('users')->where('total_credit', '=', 100)->get();
        $data['simple_where_1'] = DB::table('users')->where('total_credit', 100)->get();
        $data['simple_where_2'] = DB::table('users')->where('total_credit', '>=', 100)->get();
        $data['simple_where_3'] = DB::table('users')->where('total_credit', '<>', 100)->get();
        $data['simple_where_4'] = DB::table('users')->where('name', 'like', 'T%')->get();
        $data['simple_where_5'] = DB::table('users')->where([['status', '=', '1'], ['total_credit', '<>', '150'],])->get();

        // Or Statements
        // The orWhere method accepts the same arguments as the where method
        $data['orWhere'] = DB::table('users')->where('total_credit', '>', 100)
            ->orWhere('name', 'John')->get();

        $data['orWhere_1'] = DB::table('users')->where('total_credit', '>', 100)->orWhere(function ($query) {
            $query->where('name', 'Abigail')
                ->where('total_credit', '>', 50);
        })->get();
        // SQL: select * from users where total_credit > 100 or (name = 'Abul' and total_credit > 50)

        // whereBetween / orWhereBetween

        // The whereBetween method verifies that a column's value is between two values
        $data['where_between'] = DB::table('users')->whereBetween('total_credit', [1, 100])->get();

        // whereNotBetween / orWhereNotBetween
        // The whereNotBetween method verifies that a column's value lies outside of two values
        $data['where_not_between'] = DB::table('users')->whereNotBetween('total_credit', [1, 100])->get();

        // whereIn / whereNotIn / orWhereIn / orWhereNotIn
        // The whereIn method verifies that a given column's value is contained within the given array
        $data['whereIn'] = DB::table('users')->whereIn('id', [1, 2, 3])->get();

        // The whereNotIn method verifies that the given column's value is not contained in the given array
        // If you are adding a huge array of integer bindings to your query,
        //the whereIntegerInRaw or whereIntegerNotInRaw methods may be used
        //to greatly reduce your memory usage.
        $data['whereNotIn'] = DB::table('users')->whereNotIn('id', [1, 2, 3])->get();

        // whereNull / whereNotNull / orWhereNull / orWhereNotNull

        // The whereNull method verifies that the value of the
        // given column is NULL
        $data['whereNull'] = DB::table('users')->whereNull('updated_at')->get();

        // The whereNotNull method verifies that the column's
        // value is not NULL
        $data['whereNotNull'] = DB::table('users')->whereNotNull('updated_at')->get();

        // whereDate / whereMonth / whereDay / whereYear / whereTime

        // The whereDate method may be used to compare a column's value against a date
        $data['whereDate'] = DB::table('users')->whereDate('created_at', '2016-12-31')->get();

        // The whereMonth method may be used to compare a
        //column's value against a specific month of a year
        $data['whereMonth'] = DB::table('users')->whereMonth('created_at', '12')->get();

        // The whereDay method may be used to compare a
        //column's value against a specific day of a month
        $data['whereDay'] = DB::table('users')->whereDay('created_at', '31')->get();

        // The whereYear method may be used to compare a
        //column's value against a specific year
        $data['whereYear'] = DB::table('users')->whereYear('created_at', '2016')->get();

        // The whereTime method may be used to compare a
        //column's value against a specific time
        $data['whereTime'] = DB::table('users')->whereTime('created_at', '=', '11:20:45')->get();

        // whereColumn / orWhereColumn

        // The whereColumn method may be used to verify that
        //two columns are equal
        $data['whereColumn'] = DB::table('users')->whereColumn('name', 'name')->get();

        // You may also pass a comparison operator to the method
        $data['whereColumn_1'] = DB::table('users')->whereColumn('updated_at', '>', 'created_at')->get();

        // The whereColumn method can also be passed an array of multiple conditions.
        $data['whereColumn_2'] = DB::table('users')->whereColumn([
            ['name', '=', 'name'],
            ['updated_at', '>', 'created_at'],
        ])->get();

        // Parameter Grouping

        $data['Parameter_Grouping'] = DB::table('users')->where('name', '=', 'John')->where(function ($query) {
            $query->where('total_credit', '>', 100)
                ->orWhere('name', '=', 'Abul');
        })->get();

        // Where Exists Clauses
        // The whereExists method allows you to write where exists SQL clauses.
        $data['whereExists'] = DB::table('users')->whereExists(function ($query) {
            $query->select(DB::raw(1))->from('course_teachers')->whereRaw('course_teachers.id = users.course_teacher_id');
        })->get();
        // select * from userswhere exists ( select 1 from orders where course_teachers.id = users.course_teacher_id)

        // Subquery Where Clauses
        $data['Subquery'] = User::where(function ($query) { $query->select('name')
            ->from('users')
            ->whereColumn('id', 'users.id')
            ->orderByDesc('created_at')
            ->limit(1);
        }, 'Pro')->get();

        // Ordering, Grouping, Limit & Offset

        // The orderBy method allows you to sort the result of
        // the query by a given column.
        // the sort and may be either asc or desc
        $data['orderBy'] = DB::table('users')->orderBy('name', 'desc')->get();

        // The latest and oldest methods allow you to easily order results by date.
        $data['latest'] = DB::table('users')->latest()->first();

        // The inRandomOrder method may be used to sort the query results randomly.
        $data['inRandomOrder'] = DB::table('users')->inRandomOrder()->first();

        // The reorder method allows you to remove all the
        //existing orders and optionally apply a new order
        $query = DB::table('users')->orderBy('name');
        $data['unorderedUsers'] = $query->reorder()->get();

        // To remove all existing orders and apply a new order,
        //provide the column and direction as arguments to the
        //method
        $query = DB::table('users')->orderBy('name');
        $data['usersOrderedByEmail'] = $query->reorder('email', 'desc')->get();

        // The groupBy and having methods may be used to
        //group the query results. The having method's
        //signature is similar to that of the where method
        $data['groupBy'] = DB::table('users')
            ->groupBy('course_teacher_id')
            ->having('course_teacher_id', '=', 1)
            ->get();

        // You may pass multiple arguments to
        //the groupBy method to group by multiple columns
        $data['groupBy_multiple'] = DB::table('users')
            ->groupBy('course_teacher_id', 'status')
            ->having('total_credit', '>', 100)->get();

        // To limit the number of results returned from the
        //query, or to skip a given number of results in the
        //query, you may use the skip and take methods
        $data['skip'] = DB::table('users')->skip(2)->take(3)->get();
        $data['offset'] = DB::table('users')->offset(2)->limit(2)->get();

        // Inserts
        $data['inserts'] = DB::table('users')->insert( ['email' => 'john@example.com', 'total_credit' => 0] );
        $data['insert_1'] = DB::table('users')
            ->insert([ ['email' => 'taylor@example.com', 'total_credit' => 0],
                       ['email' => 'dayle@example.com', 'total_credit' => 0]
                    ]);

        // The insertOrIgnore method will ignore duplicate record errors while inserting records into the database
        $data['insertOrIgnore'] = DB::table('users')
            ->insertOrIgnore([ ['id' => 1, 'email' => 'taylor@example.com'], ['id' => 2, 'email' => 'dayle@example.com'] ]);

        // Updates
        $data['Updates'] = DB::table('users')->where('id', 1)->update(['total_credit' => 1]);

        // The updateOrInsert method will first attempt to locate a matching database record using the first argument's column and value pairs.
        $data['updateOrInsert'] = DB::table('users')->updateOrInsert( ['email' => 'john@example.com', 'name' => 'John'], ['total_credit' => '2'] );

        // Deletes
        $data['deletes'] = DB::table('users')->delete();
        $data['deletes_1'] = DB::table('users')->where('total_credit', '>', 100)->delete();

        // The query builder also includes a few functions to help you do
        // "pessimistic locking" on your select statements. To run the statement
        // with a "shared lock", you may use the sharedLock method on a query.
        // A shared lock prevents the selected rows from being modified until your transaction commits
        $data['pessimistic_locking'] = DB::table('users')
            ->where('total_credit', '>', 100)
            ->sharedLock()->get();

        // Alternatively, you may use the lockForUpdate method.
        // A "for update" lock prevents the rows from being modified or
        // from being selected with another shared lock
        $data['for_update'] = DB::table('users')
            ->where('total_credit', '>', 100)
            ->lockForUpdate()->get();

        // You may use the dd or dump methods while building a query to dump
        // the query bindings and SQL. The dd method will display the debug
        // information and then stop executing the request.
        // The dump method will display the debug information but allow the request to keep executing
        $data['dd'] = DB::table('users')->where('total_credit', '>', 100)->dd();
        $data['dump'] = DB::table('users')->where('total_credit', '>', 100)->dump();

        return view('dashboard.index');
    }
}
