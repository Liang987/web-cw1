<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
// 👇 1. Import WeatherService / 引入 WeatherService
use App\Services\WeatherService;

class PostController extends Controller
{
    use AuthorizesRequests;

    protected $weatherService;

    // 🟢 2. Dependency Injection: Inject WeatherService
    // 依赖注入：注入 WeatherService
    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    /**
     * Display a listing of the resource.
     * 显示资源列表 (帖子列表)。
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get posts: eager load user, order by latest, and paginate 10 items per page
        // 获取帖子：预加载用户，按最新排序，每页分页显示 10 条
        $posts = Post::with('user')->latest()->paginate(10);

        // 🟢 3. Get current weather data via the service
        // 通过服务获取当前天气数据
        $weather = $this->weatherService->getCurrentWeather();

        // 4. Pass posts and weather data to the view
        // 将帖子和天气数据传递给视图 (compact 加上 'weather')
        return view('posts.index', compact('posts', 'weather'));
    }

    /**
     * Show the form for creating a new resource.
     * 显示创建新资源的表单。
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     * 将新创建的资源保存到存储中。
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the request data (Rubric 14)
        // 验证请求数据 (Rubric 14)
        $validated = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
            'image' => 'nullable|image|max:2048', // Optional image, max 2MB / 可选图片，最大 2MB
        ]);

        // Handle image upload if present (Rubric 16)
        // 如果有图片上传，则处理图片 (Rubric 16)
        if ($request->hasFile('image')) {
            // Store image in 'posts' directory within the public disk
            // 将图片存储在 public 磁盘的 'posts' 目录中
            $path = $request->file('image')->store('posts', 'public');
            $validated['image_path'] = $path;
        }

        // Create the post associated with the current user
        // 创建与当前用户关联的帖子
        $request->user()->posts()->create($validated);

        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified resource.
     * 显示指定的资源 (单个帖子详情)。
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\View\View
     */
    public function show(Post $post)
    {
        // Eager load comments and their authors to prevent N+1 queries
        // 预加载评论及其作者，以防止 N+1 查询问题
        $post->load('comments.user');
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     * 显示编辑指定资源的表单。
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\View\View
     */
    public function edit(Post $post)
    {
        // Check authorization: User can only edit their own post (Rubric 11)
        // 检查权限：用户只能编辑自己的帖子 (Rubric 11)
        $this->authorize('update', $post);
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     * 更新存储中的指定资源。
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Post $post)
    {
        // Check authorization
        // 检查权限
        $this->authorize('update', $post);

        // Validate update data
        // 验证更新数据
        $validated = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        // Handle image update logic
        // 处理图片更新逻辑
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $validated['image_path'] = $path; // Update with new image path / 更新为新图片路径
        }
        // Note: If no new image is uploaded, 'image_path' is not in $validated, so old image remains.
        // 注意：如果没有上传新图片，$validated 中就没有 'image_path'，因此旧图片会保留。

        // Update the post record
        // 更新帖子记录
        $post->update($validated);

        return redirect()->route('posts.index')->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     * 从存储中移除指定的资源。
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Post $post)
    {
        // Check authorization: User can only delete their own post (Rubric 11)
        // 检查权限：用户只能删除自己的帖子 (Rubric 11)
        $this->authorize('delete', $post);
        
        // Delete the post
        // 删除帖子
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted!');
    }
}