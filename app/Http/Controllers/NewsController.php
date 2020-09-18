<?php

namespace App\Http\Controllers;

use App\Models\News;
use Carbon\Carbon;

class NewsController extends Controller
{
    //

    /***
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     *
     * @uses curl
     * @description
     * this function uses curl to get the resource from the api endpoint which contains an timestamp, articleCount and an array of articles
     *
     * on successful retrieval, i looped through the articles array and inserted the current array in the loop into the database
     * and the returned the "welcome view (page)" to the client containing a response
     *
     * in case of an Exception,  a response indicating the error is set to the client
     */

    public function index(){
        $response = null;
       $url = "https://gnews.io/api/v3/search?q=none&token=16d422f0d4bfe835e0c18d5dd580b3e5";
       try{
            $connection = curl_init();

            // set url
            curl_setopt($connection, CURLOPT_URL,  $url);
            curl_setopt($connection, CURLOPT_FAILONERROR, true);

           // return the transfer as a string
            curl_setopt($connection, CURLOPT_RETURNTRANSFER, true);

            // $output contains the output string
            $output = curl_exec($connection);

              $response = json_decode($output,true);

        }catch(\Throwable $ex){
            return view('.welcome')->withErrors($ex->getMessage() );
        } finally {
            // close curl resource to free up system resources
            curl_close($connection);
        }

        return $this->checkResponse($response);

        return view('.welcome')->withErrors($response);

    }

    private function checkResponse($response){
        if(!isset($response)){
            return view('.welcome')->withErrors(['errors'=>'Could not get required resource']);
        }

        if(isset($response['articleCount']) && $response['articleCount'] >0 ) {
            $this->addRecord($response);
            return view('.welcome')->with('No_of_articles',$response['articleCount']);
        }
    }

    /***
     * @param $response
     * this function inserts a new record from the endpoint to the database
     */
    private function addRecord($response){
            $news = new News();
            foreach($response['articles'] as $key=>$article){
                $news->title = $article['title'];
                $news->description = $article['description'];
                $news->url= $article['url'];
                $news->image = $article['image'];
                $news->publishedAt = Carbon::make($article['publishedAt']);
                $news->source_name = $article['source']['name'];
                $news->source_url = $article['source']['url'];
                $news->save();
            }

    }
}
