<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'clearance'])->except('index', 'show','upload');
    }

    /**
     * 显示文章列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $posts = Post::orderby('id', 'desc')->paginate(5); //show only 5 items at a time in descending order

        return view('posts.index', compact('posts'));
    }

    /**
     * 显示创建文章表单
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('posts.create');
    }

    /**
     * 存储新增文章
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        //验证 title 和 body 字段
        $this->validate($request, [
            'title'=>'required|max:100',
            'body' =>'required',
        ]);

        $post = Post::create($request->only('title', 'body'));

        // 基于保存结果显示成功消息
        return redirect()->route('posts.index')
            ->with('flash_message', 'Article,
             '. $post->title.' created');
    }

    /**
     * 保存图片
     * @param Request $request
     */
    public function upload(Request $request){
        if ($request->isMethod('post')) {

            $file = $request->file('image');


            // 文件是否上传成功
            if ($file->isValid()) {

                // 获取文件相关信息
                //$originalName = $file->getClientOriginalName(); // 文件原名
                $ext = $file->getClientOriginalExtension();     // 扩展名
                $realPath = $file->getRealPath();   //临时文件的绝对路径
                //$type = $file->getClientMimeType();     // image/jpeg

                // 上传文件
                $filename = date('Y-m-d-H-i-s') . '-' . uniqid() . '.' . $ext;
                // 使用我们新建的uploads本地存储空间（目录）
                //这里的uploads是配置文件的名称
                $bool = Storage::put($filename, file_get_contents($realPath));
                if($bool){
                    return array("success"=>1,"image"=>array("path"=>"../storage/".$filename,'desc'=>0));
                }else{
                    return array("success"=>0,"message"=>'上传失败！');
                }



            }
        }
    }

    /**
     * 显示指定资源
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $post = Post::findOrFail($id); //通过 id = $id 查找文章

        return view ('posts.show', compact('post'));
    }

    /**
     * 显示编辑文章表单
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $post = Post::findOrFail($id);

        return view('posts.edit', compact('post'));
    }

    /**
     * 更新文章
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $this->validate($request, [
            'title'=>'required|max:100',
            'body'=>'required',
        ]);

        $post = Post::findOrFail($id);
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->save();

        return redirect()->route('posts.show',
            $post->id)->with('flash_message',
            'Article, '. $post->title.' updated');

    }

    /**
     * 删除文章
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $post = Post::findOrFail($id);
        $post->delete();

        return redirect()->route('posts.index')
            ->with('flash_message',
                'Article successfully deleted');

    }
}
