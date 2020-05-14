<?php

namespace App\Http\Controllers;
use App\Events\PostViewEvent;
use App\Http\Requests\CreatePostRequest;
use App\post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function Sodium\crypto_box_publickey_from_secretkey;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // return'ID is :'." ".$id;       $id=null
       // echo asset('storage/images.jpg');
        $posts = Post::with('user')->get();
        return view('posts.index',compact(['posts']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function create()
   {
       return view('posts.create');
   }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePostRequest $request)  //CreatePostRequest
    {
       //  $file = $request->file('file');
        // echo  $file->getSize();

        //dd('hello');
       // return $request->all();
//        //return $request->input('title');
//        $this->validate($request,[
//            'title'=>'bail|required|max:2',
//            'description'=>'required'
//    ],[
//            'title.required'=>'لطفا عنوان مطلب مورد نظر خود را وارد کنید',
//            'title.max'=>'تعداد کاراکترهای عنوان شما باید کمتر از دو کاراکتر باشد',
//            'description.required'=> 'لطفا توضیحات مطلب مورد نظر خود را وارد کنید'
//        ]);
        $post=new post();
        if ( $file = $request->file('file')){
             // $name=$file->getClientOriginalName();
              $file->store('public/images');
//              $file->move('images',$name);
//              $post->path=$name;
        }

        $post->title = $request->title;
        $post->content = $request->description;
        $post->user_id = 1;
        $post->save();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post=Post::findOrFail($id);
        event( new PostViewEvent($post));
        return view('posts.show',compact(['post']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $post=Post::findOrFail($id);
        $user = Auth::user();
        if($user->can('update', $post)){
            return view('posts.edit',compact(['post']));
        }else{
            return "شما اجازه ویرایش این مطلب را ندارید";
        }



//        if(Gate::denies('edit-post' , $post)){
//            return "شما اجازه ویرایش این مطلب را ندارید";
//        }else{
//            return view('posts.edit',compact(['post']));
//        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post=Post::findOrFail($id);
        $post->title=$request->title;
        $post->content=$request->description;
        $post->save();
        //$post->update($request->all());
        return redirect('posts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $post=Post::findOrFail($id);
       $post->delete();
      return redirect('posts');
    }
//    public function showMyView($id,$name,$password){
//       // return view('pages.myView')->with('id',$id);
//        return view('pages.myView',compact(['id','name','password']));
//    }
//
//    public function contact()
//    {
//       $people=['مسعود','علی','مریم','میلاد','حسن','ماریا'];
//       return view('pages.contact',compact('people'));
//    }
//
//    public function insert()
//    {
//        DB::insert('insert into posts(title,content)values(?,?)',['Insert پست','این متد با استفاده از insert درج شده است']);
//    }
//
//    public function select()
//    {
//      $allPosts = DB::select('select * from posts');
//      return $allPosts;
//    }
//
//    public function updatePost()
//    {
//      $updatedPost=DB::update('update posts set title="title updated" where id=?',[2]);
//      return $updatedPost;
//    }
//
//    public function deletePost()
//    {
//        $deletedPost=DB::delete('delete from posts where id=?',[2]);
//        return $deletedPost;
//    }
//
//    public function getAllPosts()
//    {
//      //$post=Post::where('title','Insert پست')->orderBy('id','desc')->take(2)->get();
//        //$post=Post::findOrFail(500);
//        $post=Post::all();
//        return $post;
//    }
//
//    public function savePost()
//    {
//       //$post = new Post();
//
//       //$post->title = 'پست شماره 1';
//       //$post->content = 'این هم یک توضیح تست برای این کانتنت می باشد.';
//
//       //$post->save();
//        $post=Post::create(['title'=>'پست شماره 2','content'=>'این هم یک توضیح جدید']);
//    }
//
//    public function newUpdatePost()
//    {
//       //$post=Post::where('id',12)->update(['title'=>'Updated post','content'=>'Update content to you']);
//        $post=Post::findOrFail(12);
//        $post->title='یک پست جدید';
//        $post->content='یک متن جدید';
//        $post->save();
//       return $post;
//    }
//
//    public function newDeletePost()
//    {
//       //$post=Post::where('id',11)->first();
//      // $post->delete();
//       // $post=Post::destroy(10);
//       // $post=Post::destroy([8,9]);
//        $post=Post::where('id',7)->delete();
//    }
//
//    public function workWithTrash()
//    {
//       //$post=Post::withTrashed()->get();
//        //$post=Post::onlyTrashed()->get();
//        $post=Post::onlyTrashed()->where('is_admin',0)->get();
//       return $post;
//    }
//
//    public function restorePost()
//    {
//       $post=Post::onlyTrashed()->where('id',7)->restore();
//    }
//
//    public function forceDelete()
//    {
//        $post=Post::onlyTrashed()->where('id',7)->forceDelete();
//    }
}
