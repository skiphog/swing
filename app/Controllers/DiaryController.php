<?php

namespace App\Controllers;

use App\Models\Diary;
use System\Http\Request;
use System\Foundation\Controller;
use System\Http\Exceptions\NotFoundException;

/**
 * Class DiaryController
 *
 * @package App\Controllers
 */
class DiaryController extends Controller
{
    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        [$diaries, $paging] = Diary::all($request->get('page'));
        $paginate = render('partials/paginate', ['paginate' => $paging, 'link' => '/diaries']);

        return view('diaries/index', compact('diaries', 'paginate'));
    }

    /**
     * @param $id
     *
     * @return \System\Http\Response
     * @throws NotFoundException
     */
    public function show($id)
    {
        if (!$diary = Diary::findById($id)) {
            throw new NotFoundException('Дневник не существует или удален');
        }

        return view('diaries/show', compact('diary'));
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return view('diaries/user');
    }
}
