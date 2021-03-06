<?php
/**
 * Copyright (c) 2011-2013 Antoine Hedgecock.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of the
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @author      Antoine Hedgecock <antoine@pmg.se>
 * @author      Jonas Eriksson <jonas@pmg.se>
 *
 * @copyright   2011-2013 Antoine Hedgecock
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
 */

namespace MCNLinkedIn\Options\Authentication\Adapter;

use MCNUser\Options\Authentication\Adapter\AbstractAdapterOptions;

/**
 * Class LinkedIn
 * @package MCNLinkedIn\Options\Authentication\Adapter
 */
class LinkedIn extends AbstractAdapterOptions
{
    /**
     * LinkedIn ID property on the user entity
     *
     * @var string
     */
    protected $entityIdProperty = 'linkedInId';

    /**
     * LinkedIn accessToken on the user entity
     *
     * @var string
     */
    protected $entityTokenProperty = 'linkedInAccessToken';

    /**
     * LinkedIn tokenExpiration date on the user entity
     *
     * @var string
     */
    protected $entityTokenExpiresAtProperty = 'linkedInTokenExpiresAt';

    /**
     * Class name of representing adapter
     *
     * @return string
     */
    public function getClassName()
    {
        return 'MCNLinkedIn\Authentication\Adapter\LinkedIn';
    }

    /**
     * Adapter alias
     *
     * @return string
     */
    public function getAdapterManagerAlias()
    {
        return 'linkedin';
    }

    /**
     * SL alias
     *
     * @return string
     */
    public function getServiceManagerAlias()
    {
        return 'mcn.authentication.adapter.linkedin';
    }

    /**
     * @param string $entityIdProperty
     */
    public function setEntityIdProperty($entityIdProperty)
    {
        $this->entityIdProperty = $entityIdProperty;
    }

    /**
     * @return string
     */
    public function getEntityIdProperty()
    {
        return $this->entityIdProperty;
    }

    /**
     * @param string $entityTokenExpiresAtProperty
     */
    public function setEntityTokenExpiresAtProperty($entityTokenExpiresAtProperty)
    {
        $this->entityTokenExpiresAtProperty = $entityTokenExpiresAtProperty;
    }

    /**
     * @return string
     */
    public function getEntityTokenExpiresAtProperty()
    {
        return $this->entityTokenExpiresAtProperty;
    }

    /**
     * @param string $entityTokenProperty
     */
    public function setEntityTokenProperty($entityTokenProperty)
    {
        $this->entityTokenProperty = $entityTokenProperty;
    }

    /**
     * @return string
     */
    public function getEntityTokenProperty()
    {
        return $this->entityTokenProperty;
    }
}
