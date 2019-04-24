<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth:api', ['except' => ['login', 'register']]);
	}

	/**
	 * Get a JWT via given credentials.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */

	public function login(Request $request)
	{
		$credentials = $request->only('email', 'password');

		if ($token = $this->guard()->attempt($credentials)) {
			return $this->respondWithToken($token);
		}

		return response()->json(['error' => 'Sai tên tài khoản hoặc mật khẩu'], 401);
	}

	/**
	 * Get the authenticated User.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function me()
	{
		return response()->json($this->guard()->user(), 200);
	}

	/**
	 * Log the user out (Invalidate the token).
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function logout()
	{
		$this->guard()->logout();

		return response()->json(['message' => 'Successfully logged out']);
	}

	/**
	 * Refresh a token.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function refresh()
	{
		return $this->respondWithToken($this->guard()->refresh());
	}

	/**
	 * Get the token array structure.
	 *
	 * @param string $token
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function respondWithToken($token)
	{
		return response()->json([
			'access_token' => $token,
			'token_type' => 'bearer',
			'expires_in' => $this->guard()->factory()->getTTL() * 60,
		]);
	}

	/**
	 * Get the guard to be used during authentication.
	 *
	 * @return \Illuminate\Contracts\Auth\Guard
	 */
	public function guard()
	{
		return Auth::guard('api');
	}

	public function register(RegisterRequest $request)
	{
		$user = User::create($request->all());
		return $this->login($request);
	}

	public function update(Request $request)
	{
		$user = $this->guard()->user();
		$user->fill($request->all());
		if ($request->image) {
			$image = $request->image;
			$path = Storage::disk('public')->put('image', $image);
			$user->image = $path;
		}
		$user->save();
		return $user;
	}

	public function createBlog(Request $request)
	{
		$user = $this->guard()->user();
		$post = new Post();
		$post->title = $request->title;
		$post->description = $request->description;
		$post->content = $request->content;
		$post->user_id = $user->id;
		if ($request->image) {
			$image = $request->image;
			$path = Storage::disk('public')->put('image', $image);
			$post->image = $path;
		}
		$post->save();
		return response()->json($post);
	}

	public function showBlogs()
	{
		$user = $this->guard()->user();
		return $user->posts;
	}
	public function deleteBlog($id) {
		$user = $this->guard()->user();
		$post = Post::findOrFail($id);
		$post->delete();
		return response()->json('Delete successfully');
	}
	public function showBlogDetail($id) {
		$user = $this->guard()->user();
		$post = Post::findOrFail($id);
		return $post;
	}
	public function updateBlog(Request $request, $id) {
		$user = $this->guard()->user();
		$post = Post::findOrFail($id);
		$post->title = $request->title;
		$post->description = $request->description;
		$post->content = $request->content;
		$post->user_id = $user->id;
		if ($request->image) {
			$image = $request->image;
			$path = Storage::disk('public')->put('image', $image);
			$post->image = $path;
		}
		$post->save();
		return response()->json($post);
	}

}