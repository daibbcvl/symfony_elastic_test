  if($trip->getState()== Trip::STATE_OFFER_SENT)
        {

            $query = new BoolQuery();
            $query->add(new RangeQuery(
                'current_booking.cancelation_policy.date',
                [RangeQuery::GTE => date_format($trip->getCurrentBooking()->getCancelationPolicy()->getDate(), \DateTime::ISO8601)]
            ), BoolQuery::MUST);


            $query->add(new RangeQuery(
                'check_in_date',
                [RangeQuery::GT => date_format($trip->getCheckInDate(), \DateTime::ISO8601)]
            ), BoolQuery::MUST);
            $query->add(new TermQuery('trip_id', $trip->getTripId()), BoolQuery::MUST_NOT);


            $functionScoreQuery = new FunctionScoreQuery(new MatchAllQuery());
            $stateTermQuery = new TermQuery('state', Trip::STATE_OFFER_SENT);
            $functionScoreQuery->addWeightFunction(Trip::WEIGHT_OF_STATE_OFFER_SENT, $stateTermQuery);

            $tripWithOptionsQuery = new BoolQuery();
            $tripWithOptionsQuery->add(new TermQuery('options_count',0), BoolQuery::MUST);
            $tripWithOptionsQuery->add(new TermQuery('state', Trip::STATE_OFFER_SENT), BoolQuery::MUST);


            $functionScoreQuery = new FunctionScoreQuery(new MatchAllQuery());
            $stateTermQuery = new TermQuery('state', Trip::STATE_OFFER_SENT);
            $functionScoreQuery->addWeightFunction(Trip::WEIGHT_OF_STATE_OFFER_SENT, $stateTermQuery);

            $search = $this->repository->createSearch();
            $search
                ->addQuery($functionScoreQuery, BoolQuery::MUST)
                ->addQuery($tripWithOptionsQuery)
                ->addQuery($query)
                ->addSort(new FieldSort('current_booking.cancelation_policy.date', FieldSort::ASC, [
                    'missing' => '_last'
                ]))
                ->addSort(new FieldSort('check_in_date', FieldSort::ASC))
                ->setSize(1);
            $tripResult = $this->repository->execute($search)->first();

            if(null == $tripResult)
            {
                $query = new BoolQuery();
                $query->add(new TermQuery('trip_id', $trip->getTripId()), BoolQuery::MUST_NOT);

                $tripWithOptionsQuery = new BoolQuery();
                $tripWithOptionsQuery->add(new RangeQuery('options_count', [RangeQuery::GT => 0]), BoolQuery::SHOULD);
                $tripWithOptionsQuery->add(new TermQuery('state', Trip::STATE_OFFER_SENT), BoolQuery::MUST_NOT);
                $tripWithOptionsQuery->add(new TermQuery('state', Trip::STATE_READY_TO_OFFER), BoolQuery::MUST_NOT);

                $search = $this->repository->createSearch();
                $search
                    ->addQuery($tripWithOptionsQuery)
                    ->addQuery($query)
                    ->addSort(new FieldSort('current_booking.cancelation_policy.date', FieldSort::ASC, [
                        'missing' => '_last'
                    ]))
                    ->addSort(new FieldSort('check_in_date', FieldSort::ASC))
                    ->setSize(1);

                return $this->repository->execute($search)->first();
            }

            return  $tripResult;
        }
        else
        {
            $query = new BoolQuery();
            $query->add(new RangeQuery(
                'current_booking.cancelation_policy.date',
                [RangeQuery::GT => date_format($trip->getCurrentBooking()->getCancelationPolicy()->getDate(), \DateTime::ISO8601)]
            ), BoolQuery::SHOULD);

            $createdAtQuery = new BoolQuery();
            $createdAtQuery->add(new TermQuery(
                'current_booking.cancelation_policy.date',
                date_format($trip->getCurrentBooking()->getCancelationPolicy()->getDate(), \DateTime::ISO8601)
            ));
//
            $createdAtQuery->add(new RangeQuery(
                'check_in_date',
                [RangeQuery::GT => date_format($trip->getCheckInDate(), \DateTime::ISO8601)]
            ));
            $query->add($createdAtQuery, BoolQuery::SHOULD);

            $tripWithOptionsQuery = new BoolQuery();
            $tripWithOptionsQuery->add(new RangeQuery('options_count', [RangeQuery::GT => 0]), BoolQuery::SHOULD);
            $tripWithOptionsQuery->add(new TermQuery('state', Trip::STATE_OFFER_SENT), BoolQuery::MUST_NOT);
            $tripWithOptionsQuery->add(new TermQuery('state', Trip::STATE_READY_TO_OFFER), BoolQuery::MUST_NOT);

            $search = $this->repository->createSearch();
            $search
                ->addQuery($tripWithOptionsQuery)
                ->addQuery($query)
                ->addSort(new FieldSort('_score', FieldSort::DESC))
                ->addSort(new FieldSort('current_booking.cancelation_policy.date', FieldSort::ASC, [
                    'missing' => '_last'
                ]))
                ->addSort(new FieldSort('check_in_date', FieldSort::ASC))
                ->setSize(1);
            
            return $this->repository->execute($search)->first();
        }
