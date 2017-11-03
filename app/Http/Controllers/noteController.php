<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatenoteRequest;
use App\Http\Requests\UpdatenoteRequest;
use App\Repositories\noteRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class noteController extends AppBaseController
{
    /** @var  noteRepository */
    private $noteRepository;

    public function __construct(noteRepository $noteRepo)
    {
        $this->noteRepository = $noteRepo;
    }

    /**
     * Display a listing of the note.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->noteRepository->pushCriteria(new RequestCriteria($request));
        $notes = $this->noteRepository->all();

        return view('notes.index')
            ->with('notes', $notes);
    }

    /**
     * Show the form for creating a new note.
     *
     * @return Response
     */
    public function create()
    {
        return view('notes.create');
    }

    /**
     * Store a newly created note in storage.
     *
     * @param CreatenoteRequest $request
     *
     * @return Response
     */
    public function store(CreatenoteRequest $request)
    {
        $input = $request->all();

        $note = $this->noteRepository->create($input);

        Flash::success('Note saved successfully.');

        return redirect(route('notes.index'));
    }

    /**
     * Display the specified note.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $note = $this->noteRepository->findWithoutFail($id);

        if (empty($note)) {
            Flash::error('Note not found');

            return redirect(route('notes.index'));
        }

        return view('notes.show')->with('note', $note);
    }

    /**
     * Show the form for editing the specified note.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $note = $this->noteRepository->findWithoutFail($id);

        if (empty($note)) {
            Flash::error('Note not found');

            return redirect(route('notes.index'));
        }

        return view('notes.edit')->with('note', $note);
    }

    /**
     * Update the specified note in storage.
     *
     * @param  int              $id
     * @param UpdatenoteRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatenoteRequest $request)
    {
        $note = $this->noteRepository->findWithoutFail($id);

        if (empty($note)) {
            Flash::error('Note not found');

            return redirect(route('notes.index'));
        }

        $note = $this->noteRepository->update($request->all(), $id);

        Flash::success('Note updated successfully.');

        return redirect(route('notes.index'));
    }

    /**
     * Remove the specified note from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $note = $this->noteRepository->findWithoutFail($id);

        if (empty($note)) {
            Flash::error('Note not found');

            return redirect(route('notes.index'));
        }

        $this->noteRepository->delete($id);

        Flash::success('Note deleted successfully.');

        return redirect(route('notes.index'));
    }
}