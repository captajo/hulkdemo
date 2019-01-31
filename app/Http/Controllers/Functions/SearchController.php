<?php

namespace App\Http\Controllers\Functions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Elasticsearch\ClientBuilder as EC;
use App\Model\Video;

class SearchController extends Controller
{
	protected $clients;

    function __construct()
    {
        //get system location for elasticsearch
        $selected_host = env('ELASTICSEARCH_HOST', '127.0.0.1');
        $selected_port = env('ELASTICSEARCH_PORT', '9200');
        $selected_scheme = env('ELASTICSEARCH_SCHEME', 'http');

        $host_address = $selected_scheme.'://'.$selected_host.':'.$selected_port;
        
        $host = [$host_address];
        $this->clients = EC::create()->setHosts($host)->build();
    }

    public function indexElasticSearch($all_videos = [])
    {
    	$response = false;

    	if(!count($all_videos)) {
    		//set up mapping and analyzers
	    	$this->initialSetUp();

	    	//get all videos
	    	$all_videos = Video::with(['actors', 'tags', 'categories'])->get();
    	}	    	

        //index all videos
    	foreach($all_videos as $video) {
    		$actors = $video->actors;
    		$listed_actors = $actors->pluck('title')->toArray();
    		$tags = $video->tags;
    		$listed_tags = $tags->pluck('title')->toArray();
    		$categories = $video->categories;
    		$listed_categories = $categories->pluck('title')->toArray();

    		$params = [
    			'index'=>'videos',
                'type'=>'details',
                'id'=>$video->id,
                'body'=>[
    				'title' => $video->title,
    				'description' => $video->description,
    				'actors' => $listed_actors,
    				'tags' => $listed_tags,
    				'categories' => $listed_categories,
				]
    		];


    		$response = $this->clients->index($params);
    	}

    	return $response;
    }

    public function searchDirectory(String $search, $from)
    {
        //search query
        $query = [
            "bool"=> [
                "must"=>[
		                    [
		                    	'multi_match'=> [
				                        "query"=> $search,
				                        "type"=>"cross_fields",
				                        "fields"=> ["actors^5", "title^4", 'tags^3', 'categories^2', 'description'],
				                    ]
		                    ],

		                ],
		        "should" => [
		                'multi_match'=> [
				                        "query"=> $search,
				                        "type"=>"phrase",
				                        "fields"=> ["actors^5", "title^4", "tags^3", "categories^2", "description"],
				                        "minimum_should_match"=>"25%",
				                        "slop" => 10,
				                    ]                 
                ],
            ]
        ];
        
        //run search query and get suggestions
        $result =  $this->performSearchQuery($query, $from, $search);
    	return $result;
    }


    public function performSearchQuery($query, $from = 1, $search)
    {
        //Paginate variables
        $rpp = 10;
        $next = $prev = $last = $total_result = 0;

    	$params = [
			'index'=>'videos',
			'type'=>'details',
			'body'=>[
                'query'=>$query,
                "from" => $from,
                "size" => $rpp,
                "suggest"=> [                       // suggestions section
                    "text"=> $search,
                    "simple_title_phrase"=> [
                        "phrase"=> [
                            "field"=> "title",
                            "size"=> 5,
                            "gram_size"=> 2,
                            "direct_generator"=> [ [
                                "field"=> "title",
                                "suggest_mode"=> "always"
                            ] ],
                            "highlight"=>[
                              "pre_tag"=> "<em>",
                              "post_tag"=> "</em>"
                            ]
                        ]
                    ],
                    "simple_actor_phrase"=> [
                        "phrase"=> [
                            "field"=> "actors",
                            "size"=> 5,
                            "gram_size"=> 2,
                            "direct_generator"=> [ [
                                "field"=> "actors",
                                "suggest_mode"=> "always"
                            ] ],
                            "highlight"=>[
                              "pre_tag"=> "<em>",
                              "post_tag"=> "</em>"
                            ]
                        ]
                    ],
                    "simple_tags_phrase"=> [
                        "phrase"=> [
                            "field"=> "tags",
                            "size"=> 5,
                            "gram_size"=> 2,
                            "direct_generator"=> [ [
                                "field"=> "tags",
                                "suggest_mode"=> "always"
                            ] ],
                            "highlight"=>[
                              "pre_tag"=> "<em>",
                              "post_tag"=> "</em>"
                            ]
                        ]
                    ],
                    "simple_category_phrase"=> [
                        "phrase"=> [
                            "field"=> "tags",
                            "size"=> 5,
                            "gram_size"=> 2,
                            "direct_generator"=> [ [
                                "field"=> "categories",
                                "suggest_mode"=> "always"
                            ] ],
                            "highlight"=>[
                              "pre_tag"=> "<em>",
                              "post_tag"=> "</em>"
                            ]
                        ]
                    ],
                    "simple_description_phrase"=> [
                        "phrase"=> [
                            "field"=> "tags",
                            "size"=> 5,
                            "gram_size"=> 2,
                            "direct_generator"=> [ [
                                "field"=> "description",
                                "suggest_mode"=> "always"
                            ] ],
                            "highlight"=>[
                              "pre_tag"=> "<em>",
                              "post_tag"=> "</em>"
                            ]
                        ]
                    ],
                    "my-suggestion" => [
                        "text" => $search,
                        "term" => [
                            "field" => "title",
                            "suggest_mode"=> "always"
                        ]
                    ],
                    "my-actor-suggestion" => [
                        "text" => $search,
                        "term" => [
                            "field" => "actors",
                            "suggest_mode"=> "always"
                        ]
                    ],
                    "my-tag-suggestion" => [
                        "text" => $search,
                        "term" => [
                            "field" => "tags",
                            "suggest_mode"=> "always"
                        ]
                    ],
                    "my-description-suggestion" => [
                        "text" => $search,
                        "term" => [
                            "field" => "description",
                            "suggest_mode"=> "always"
                        ]
                    ],
                    "my-category-suggestion" => [
                        "text" => $search,
                        "term" => [
                            "field" => "categories",
                            "suggest_mode"=> "always"
                        ]
                    ]
                ]
			]
        ];

		$search = $this->clients->search($params);

        //check if there is a next page
        $total_result = $search['hits']['total'];

        $page_data =[
            "rpp" => $rpp,
            "current" => $from,
            "total_result" => $total_result
        ];


        $info = [
            'result'=>$search['hits']['hits'],
            'suggest'=>$search['suggest'],
            "page_data" => $page_data
        ];

        return $info;
    }

    //delete elasticsearch index
    public function deleteAllIndex()
    {
        $response = false;

        $params = [
              'index'=>'videos',
                  ];

        $response = $this->clients->indices()->delete($params);

        return $response;
    }

    //re-index elasticsearch
    public function reIndexLibrary()
    {
    	$indexParams = ['index' => 'videos'];
    	//check if any index exist
 		if($this->clients->indices()->exists($indexParams)) {
 			$this->deleteAllIndex();
 		}

 		//if an error occured re-indexing
 		if(!$this->indexElasticSearch())
 			return false;

 		return true;
    }

    //count record in elastic search
    public function countIndexRecord()
    {
    	$indexParams = ['index' => 'videos', 'type'=>'details'];
    	return $this->clients->count($indexParams);
    }

    //update entries in elasticsearch index
    public function updateEntries($videos)
    {
    	foreach($videos as $video) {
    		// 
    		$params = [
              'index'=>'videos',
              'type' => 'details',
              'id' => $video->id
                  ];
            $response = $this->clients->delete($params);

    		$actors = $video->actors;
    		$listed_actors = $actors->pluck('title')->toArray();
    		$tags = $video->tags;
    		$listed_tags = $tags->pluck('title')->toArray();
    		$categories = $video->categories;
    		$listed_categories = $categories->pluck('title')->toArray();

    		$params = [
    			'index'=>'videos',
                'type'=>'details',
                'id'=>$video->id,
                'body'=>[
    				'title' => $video->title,
    				'description' => $video->description,
    				'actors' => $listed_actors,
    				'tags' => $listed_tags,
    				'categories' => $listed_categories,
				]
    		];

    		$response = $this->clients->index($params);
    	}
    	return true;
    }

    private function initialSetUp()
    {
        //Elasticsearch Index Initialization Parameters
           $params = [
                'index' => 'videos',
                'body' => [
                    'settings' => [
                        'number_of_shards' => 1,
                        "analysis"=> [
                            "filter"=> [
                                "my_shingle_filter"=> [
                                    "type"=>   "shingle",
                                    "min_shingle_size"=> 2, 
                                    "max_shingle_size"=> 4, 
                                    "output_unigrams"=> false,
                                    "catenate_words"=>true 
                                ],
                            ],
                            "analyzer"=> [
                                "my_shingle_analyzer"=> [
                                    "type"=>             "custom",
                                    "tokenizer"=>        "standard",
                                    "filter"=> [
                                    	"standard",
                                        "lowercase",
                                        "my_shingle_filter",
                                    ]
                                ],
                            ]
                        ]
                    ],
                    'mappings' => [
                        'details' => [
                            'properties' => [
                                "actor"=> [ 
                                    "type"=>     "text",
                                    "analyzer"=> "standard",
                                    "position_increment_gap" => 100,
                                    "fields"=> [
                                        "shingles"=> [
                                            "type"=>     "text",
                                            "analyzer"=> "my_shingle_analyzer"
                                        ]
                                    ]
                                ],
                                "title"=>[
                                    "type"=>     "text",
                                    "analyzer"=> "standard",
                                    "fields"=> [
                                        "shingles"=> [
                                            "type"=>     "text",
                                            "analyzer"=> "my_shingle_analyzer"
                                        ]
                                    ]
                                ],
                                "tag"=>[
                                    "type"=>     "text",
                                    "analyzer"=> "standard",
                                    "position_increment_gap" => 100,
                                    "fields"=> [
                                        "shingles"=> [
                                            "type"=>     "text",
                                            "analyzer"=> "my_shingle_analyzer"
                                        ]
                                    ]
                                ],
                                "category"=>[
                                    "type"=>     "text",
                                    "analyzer"=> "standard",
                                    "position_increment_gap" => 100,
                                ],
                                "description"=>[
                                    "type"=>     "text",
                                    "analyzer"=> "standard",
                                    "fields"=> [
                                        "shingles"=> [
                                            "type"=>     "text",
                                            "analyzer"=> "my_shingle_analyzer"
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ];

            $response = $this->clients->indices()->create($params);
    }

    
    
}
