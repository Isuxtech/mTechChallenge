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
     * this function uses url to get the resource from the api endpoint whcih contains an teimstamp, articleCount and an array of articles
     *
     * on successful retrival, i looked through the articles array and inserted the current array in the loop into the database
     * and the return the "welcome view (page)" to the client containing a response
     *
     * in case of am Exception,  a response indicating the error is set to the client
     */

    public function index(){
        $response = null;
       $url = "https://gnews.io/api/v3/search?q=none&token=16d422f0d4bfe835e0c18d5dd580b3e5";
//       $url = "https://gnews.io/api/v3/search?q=none&token=1db2bbd53ffbfc069ef5cf6b35e7f0f8a"; my personal key
        try{
            $ch = curl_init();

            // set url
            curl_setopt($ch, CURLOPT_URL,  $url);

           // return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // $output contains the output string
            $output = curl_exec($ch);

             $response = json_decode($output,true);
//            return $response['articles'];
        }catch(\Throwable $ex){
            return view('.welcome')
                ->withErrors($ex->getMessage() );
        } finally {
            // close curl resource to free up system resources
            curl_close($ch);
        }
        if(isset($response['articleCount']) && $response['articleCount'] >0 ){
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
            return view('.welcome')
                ->with('No_of_articles',$response['articleCount']);
        }
        return view('.welcome')
            ->withErrors($response);

    }
}
