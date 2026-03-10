<?php

namespace App\Repositories\Review;

interface ReviewRepository
{
	
	/**
     * Create Review
     *
     * @param $client_id: int
     * @param $appointment_id: int
     * @param $rating: int
     * @param $comment: string
	 * @return Review
     */
    public function createReview($client_id, $appointment_id, $rating, $comment);

	/**
     * Find Reviews
     *
	 * @return Review[]
     */
    public function getAllReviews();
	
	/**
     * paginates reviews
     *
	 * @param $searchQuery: string
     * @param $perPage: integer
     * @param $page: integer
     * @param $sortBy: string
     * @param $sortDesc: boolean
	 * @return Review[]
     */
    public function searchAndPaginate($searchQuery, $perPage, $page, $sortBy, $sortDesc);
	
	/**
     * Finds Review by id.
     *
     * @return Review
     */
    public function getById($id);
	
	/**
     * Deletes Review
     *
     * @param $review_id: int
     */
    public function delete($review_id);

}
