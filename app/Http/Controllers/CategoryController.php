<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //SELECT * FROM categories
        // $data = Category::all();
        $data = Category::with('user')->get();
        return response()->view('cms.categories.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //SELECT * FROM users;
        $users = User::all();
        return response()->view('cms.categories.create', ['users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $request->validate([]);
        $validator = Validator($request->all(), [
            'title' => 'required|string|min:3|max:30',
            'info' => 'required|string|min:3|max:150',
            // 'active' => 'nullable|string|in:on'
            'user_id' => 'required|numeric|exists:users,id',
            'active' => 'required|boolean',
        ]);

        if (!$validator->fails()) {
            //
            $category = new Category();
            $category->title = $request->input('title');
            $category->info = $request->input('info');
            $category->user_id = $request->input('user_id');
            $category->active = $request->input('active');
            $isSaved = $category->save();
            return response()->json(
                [
                    'message' => $isSaved ? "Category saved successfully" : "Failed to save category",
                    'icon' => $isSaved ? 'success' : 'error',
                ],
                $isSaved ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST
            );
        } else {
            //
            return response()->json(['message' => $validator->getMessageBag()->first(), 'icon' => 'error'], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $users = User::all();
        $category = Category::findOrFail($id);
        return response()->view('cms.categories.edit', ['category' => $category, 'users' => $users]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validator = Validator($request->all(), [
            'title' => 'required|string|min:3|max:30',
            'info' => 'required|string|min:3|max:150',
            'user_id' => 'required|numeric|exists:users,id',
            'active' => 'required|boolean',
        ]);

        if (!$validator->fails()) {
            //
            $category = Category::findOrFail($id);
            $category->title = $request->input('title');
            $category->info = $request->input('info');
            $category->user_id = $request->input('user_id');
            $category->active = $request->input('active');
            $isSaved = $category->save();
            return response()->json(
                [
                    'message' => $isSaved ? "Category saved successfully" : "Failed to save category",
                    'icon' => $isSaved ? 'success' : 'error',
                ],
                $isSaved ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST
            );
        } else {
            //
            return response()->json(['message' => $validator->getMessageBag()->first(), 'icon' => 'error'], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //DELETE FROM categories WHERE id = 1;
        //DB::table('categories')->delete($category->id);
        $deleteCount = Category::destroy($id);
        $deleted = $deleteCount == 1;
        return response()->json(
            ['message' => $deleted ? "Deleted successfully" : "Delete failed!"],
            $deleted ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST
        );
    }
}
