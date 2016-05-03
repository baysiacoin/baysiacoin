<?php namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\View;

class Handler extends ExceptionHandler {

	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		'Symfony\Component\HttpKernel\Exception\HttpException'
	];

//	/**
//	 * Report or log an exception.
//	 *
//	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
//	 *
//	 * @param  \Exception  $e
//	 * @return void
//	 */
//	public function report(Exception $e)
//	{
//
//		return parent::report($e);
//	}
//
//	/**
//	 * Render an exception into an HTTP response.
//	 *
//	 * @param  \Illuminate\Http\Request  $request
//	 * @param  \Exception  $e
//	 * @return \Illuminate\Http\Response
//	 */
//	public function render($request, Exception $e)
//	{
//		return parent::render($request, $e);
//	}
	/**
	 * Throw a 404 is a model record isn't found
	 * @param  ModelNotFoundException
	 * @return [type]
	 */
	protected function renderModelNotFoundException(ModelNotFoundException $e)
	{
		if (view()->exists('errors.404'))
		{
			return response()->view('errors.404', [], 404);
		}
		else
		{
			return (new SymfonyDisplayer(config('app.debug')))
				->createResponse($e);
		}
	}
	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	public function report(Exception $e)
	{
		return parent::report($e);
	}
	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $e
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $e)
	{
		if ($this->isHttpException($e))
		{
			return $this->renderHttpException($e);
		}
		else if ($e instanceof ModelNotFoundException)
		{
			return $this->renderModelNotFoundException($e);
		}
		else if ($e instanceof InvalidArgumentException)
		{
			return View::make('errors.404');
		}
		else if ($e instanceof TokenMismatchException)
		{
			// return View::make('errors.token_error');			
			return redirect($request->fullUrl())->with('csrf_error',"Opps! Seems you couldn't submit form for a longtime. Please try again");
		}
		else
		{
			return parent::render($request, $e);
		}
	}
}
