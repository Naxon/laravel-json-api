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

namespace CloudCreativity\LaravelJsonApi\Resolver;

class UnitNamespaceResolver extends NamespaceResolver
{

    /**
     * @inheritDoc
     */
    public function getAuthorizerByName($name)
    {
        return $this->resolve('Authorizer', $name);
    }

    /**
     * @param string $unit
     * @param string $resourceType
     * @return string
     */
    protected function resolve($unit, $resourceType)
    {
        $unit = str_plural($unit);
        $type = ucfirst(str_singular($resourceType));

        return $this->append(sprintf('%s\%s', $unit, $type));
    }

}
