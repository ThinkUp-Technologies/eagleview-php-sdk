<?php

namespace ThinkUp\EagleView\Actions;

use GuzzleHttp\Exception\GuzzleException;
use ThinkUp\EagleView\Exceptions\ApiServerException;
use ThinkUp\EagleView\Exceptions\FailedActionException;
use ThinkUp\EagleView\Exceptions\NotFoundException;
use ThinkUp\EagleView\Exceptions\ValidationException;
use ThinkUp\EagleView\Resources\Product;

trait ManagesReports
{
    /**
     * The GetReports method will return specific pieces of data for
     * a range of reports. This method is generally used as a means
     * of searching reports on the authenticated user's account.
     *
     * @see https://restdoc.eagleview.com/#GetReports
     *
     * @param int $page The page number.
     * @param int $count Count of reports to return.
     * @param array $payload All values required for the request body.
     * @return array
     * @throws ApiServerException
     * @throws FailedActionException
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function getReports(int $page, int $count, array $payload): array
    {
        return $this->post('v2/Report/GetReports', [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode($payload),
            'query' => [
                'page' => $page,
                'count' => $count,
            ],
        ]);
    }

    /**
     * The GetReport call will return information about a single report.
     * Notable pieces of information include Status, ReportDownloadLink,
     * and measurement totals.
     *
     * @see https://restdoc.eagleview.com/#GetReport
     *
     * @param int $reportId The id of the report to get.
     * @return array
     * @throws GuzzleException
     * @throws ApiServerException
     * @throws FailedActionException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function getReportV2(int $reportId): array
    {
        return $this->get('v2/Report/GetReport', ['reportId' => $reportId]);
    }

    /**
     * The GetReport call will return information about a single report.
     * Notable pieces of information include Status, ReportDownloadLink,
     * and measurement totals.
     *
     * @see https://restdoc.eagleview.com/#V3GetReport
     *
     * @param int $reportId The id of the report to get.
     * @return array
     * @throws GuzzleException
     * @throws ApiServerException
     * @throws FailedActionException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function getReportV3(int $reportId): array
    {
        return $this->get('v3/Report/GetReport', ['reportId' => $reportId]);
    }

    /**
     * The GetReportFile method allows the authenticating user to retrieve
     * files generated for a report. Please note that a report file can
     * only be retrieved if it has been generated. If the user makes a
     * request for a fileType and fileFormat that has not been generated,
     * then the call will fail. Please reach out to the Integrations team
     * to have a specific fileType and fileFormat generated.
     *
     * @see https://restdoc.eagleview.com/#GetReportFile
     *
     * @param int $reportId The id of the report to get.
     * @param int $fileType Specifies the file type for the report.
     * @param int $fileFormat Specifies the file format for the report.
     * @return string
     * @throws ApiServerException
     * @throws FailedActionException
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function getReportFile(int $reportId, int $fileType, int $fileFormat): string
    {
        return $this->get('v1/File/GetReportFile', [
            'reportId' => $reportId,
            'fileType' => $fileType,
            'fileFormat' => $fileFormat,
        ]);
    }
}
