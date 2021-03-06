<?php
/**
 * Copyright 2018 Cloud Creativity Limited
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace CloudCreativity\LaravelJsonApi\Eloquent;

use CloudCreativity\LaravelJsonApi\Contracts\Object\RelationshipInterface;
use CloudCreativity\LaravelJsonApi\Exceptions\RuntimeException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne as Relation;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;

class HasOne extends BelongsTo
{

    /**
     * @inheritDoc
     */
    public function update($record, RelationshipInterface $relationship, EncodingParametersInterface $parameters)
    {
        $relation = $this->relation($record);
        $related = $this->related($relationship);
        /** @var Model|null $current */
        $current = $record->{$this->key};

        /** If the relationship is not changing, we do not need to do anything. */
        if ($current && $related && $current->is($related)) {
            return;
        }

        /** If there is a current related model, we need to clear it. */
        if ($current) {
            $current->setAttribute($relation->getForeignKeyName(), null)->save();
        }

        /** If there is a related model, save it. */
        if ($related) {
            $relation->save($related);
        }

        // no need to refresh $record as the Eloquent adapter will do it.
    }

    /**
     * @inheritDoc
     */
    public function replace($record, RelationshipInterface $relationship, EncodingParametersInterface $parameters)
    {
        $this->update($record, $relationship, $parameters);
        $record->refresh(); // in case the relationship has been cached.

        return $record;
    }

    /**
     * @param $record
     * @return Relation
     */
    protected function relation($record)
    {
        $relation = $record->{$this->key}();

        if (!$relation instanceof Relation) {
            throw new RuntimeException("Model relation '{$this->key}' is not an Eloquent has-one relation.");
        }

        return $relation;
    }
}
